<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        return view('login');
    }
    /**
     * Show the signup form
     */
    public function showSignupForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        // Check if there's signup data from landing page/organization form
        $signupData = session('signup_data', []);
        $fromLandingPage = !empty($signupData) && isset($signupData['step']);

        return view('signup', [
            'fromLandingPage' => $fromLandingPage,
            'signupData' => $signupData,
        ]);
    }

    /**
     * Handle the signup request
     */
    /**
     * Handle initial signup from landing page
     */
    public function handleInitialSignup(Request $request)
    {
        try {
            // Validate the request with the same password rules as signup
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
            ], [
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            ]);

            // Store the validated data in the session (don't send OTP yet)
            session([
                'signup_data' => [
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'], // Store unhashed for now
                    'step' => 'organization_info', // Track current step
                ],
            ]);

            // Return JSON response for AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Please complete your organization information.',
                    'redirect' => route('signup.organization.form'),
                ]);
            }

            // Redirect to organization info form
            return redirect()->route('signup.organization.form');

        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    public function signup(Request $request)
    {
        try {
            // Check if there's data from the landing page
            $landingData = session('signup_data', []);

            // Validate the incoming request, using landing data as defaults
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
                'type' => 'required|string|in:Agency,Advertiser',
            ], [
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            ]);

            // Generate OTP
            $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            try {
                // Send OTP email
                Mail::to($validatedData['email'])->send(
                    new OtpMail($otp, $validatedData['first_name'], $validatedData['last_name'])
                );
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: '.$e->getMessage());
                throw ValidationException::withMessages([
                    'email' => ['Failed to send verification code. Please try again.'],
                ]);
            }

            // Store signup data in session with expiry
            Session::put('signup_data', [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'type' => $validatedData['type'],
                'otp' => $otp,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(10),
            ]);

            return response()->json([
                'message' => 'Verification code sent to your email.',
                'email' => substr_replace($validatedData['email'], '***', 1, strpos($validatedData['email'], '@') - 2),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Signup error: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred during signup. Please try again.',
            ], 500);
        }
    }

    public function driverLogin(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Authenticate driver
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $driver = Auth::user();

            return response()->json(['message' => 'Login successful', 'driver' => $driver], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Verify OTP
    /**
     * Verify the OTP and complete registration
     */
    public function verifyOtp(Request $request)
    {
        try {
            // Validate OTP format
            $request->validate([
                'otp' => 'required|string|size:4',
            ]);

            // Get signup data from session
            $signupData = Session::get('signup_data');

            if (! $signupData || now()->isAfter($signupData['expires_at'])) {
                Session::forget('signup_data');
                throw ValidationException::withMessages([
                    'otp' => ['Verification code has expired. Please try again.'],
                ]);
            }

            // Check remaining attempts
            if ($signupData['attempts'] >= 3) {
                Session::forget('signup_data');
                throw ValidationException::withMessages([
                    'otp' => ['Too many invalid attempts. Please restart the signup process.'],
                ]);
            }

            // Increment attempts
            $signupData['attempts']++;
            Session::put('signup_data', $signupData);

            // Verify OTP
            if ($request->otp !== $signupData['otp']) {
                throw ValidationException::withMessages([
                    'otp' => ['Invalid verification code. '.(3 - $signupData['attempts']).' attempts remaining.'],
                ]);
            }

            // Reset attempts after successful verification
            $signupData['attempts'] = 0;
            Session::put('signup_data', $signupData);

            // Don't create user yet - they need to select account type
            // Return success response to move to account type selection
            return response()->json([
                'message' => 'Email verified successfully!',
                'next_step' => 'account_type',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP verification error: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred during verification. Please try again.',
            ], 500);
        }
    }

    /**
     * Complete signup after account type selection
     */
    public function completeSignup(Request $request)
    {
        try {
            // Validate account type
            $request->validate([
                'account_type' => 'required|string|in:Agency,Advertiser',
            ]);

            // Get signup data from session
            $signupData = Session::get('signup_data');

            if (! $signupData) {
                throw ValidationException::withMessages([
                    'error' => ['Session expired. Please start the signup process again.'],
                ]);
            }

            // Create the user with all collected data
            $user = User::create([
                'name' => $signupData['first_name'].' '.$signupData['last_name'],
                'email' => $signupData['email'],
                'password' => Hash::make($signupData['password']),
                'type' => $request->account_type,
                'email_verified_at' => now(),
                'organization_name' => $signupData['organization_name'] ?? null,
                'organization_email' => $signupData['organization_email'] ?? null,
                'phone_number' => $signupData['organization_phone'] ?? null,
                'organization_address' => $signupData['organization_address'] ?? null,
            ]);

            // Clear signup session data
            Session::forget('signup_data');

            // Log the user in
            Auth::login($user);

            // Return success response - redirect to dashboard
            return response()->json([
                'message' => 'Registration completed successfully!',
                'redirect' => route('dashboard'),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Complete signup error: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $remember = $request->boolean('remember');

            if (! Auth::attempt($credentials, $remember)) {
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not match our records.'],
                ]);
            }

            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
                'redirect' => route('dashboard'),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred during login. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
            'redirect' => route('login'),
        ]);
    }
}
