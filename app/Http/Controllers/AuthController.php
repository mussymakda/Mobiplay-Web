<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Include the Auth facade
use Illuminate\Support\Facades\Validator;
use App\Mail\OtpMail; // Make sure to import the OtpMail class
use Log;// Include the Validator facade

class AuthController extends Controller
{
    protected $otp; // To hold the OTP temporarily

    // Show Signup Form
    public function showSignupForm()
    {
        return view('signup'); // Adjust as necessary
    }

    // Handle the signup
    public function signup(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|string' // Ensure account type is validated
        ]);


        // Generate OTP
        $this->otp = rand(1000, 9999);

        // Send OTP via email
        // Mail::raw("Your OTP is: {$this->otp}", function ($message) use ($validatedData) {
        //     $message->to($validatedData['email']);
        //     $message->subject('Your OTP for Signup');
        // });

        // Prepare the OTP, first name, and last name from validated data
        $otp = $this->otp; // Assuming $this->otp is defined with the OTP value

        // Create an instance of the OtpMail class
        $mail = new OtpMail($otp, $validatedData['first_name'], $validatedData['last_name']);

        // Send the email using the OtpMail class
        Mail::to($validatedData['email'])->send($mail);

        // Store the email and names in the session
        session([
            'signup_first_name' => $validatedData['first_name'],
            'signup_last_name' => $validatedData['last_name'],
            'signup_email' => $validatedData['email'],
            'account_type' => $validatedData['account_type'], // Store account type in session
        ]);

        session(['signup_email' => $validatedData['email']]);
        session(['signup_first_name' => $validatedData['first_name']]);
        session(['signup_last_name' => $validatedData['last_name']]);
        session(['otp' => $this->otp]); // Store OTP in session
        session(key: ['account_type' => $validatedData['account_type']]);
        session(['signup_password' => $validatedData['password']]);
        return response()->json(['message' => 'OTP sent to your email.'], 200);
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
    public function verifyOtp(Request $request)
    {
        console.log(session('otp'));
        $request->validate([
            'otp' => 'required|numeric|digits:4',
        ]);


        // Check if the OTP matches
        if (intval($request->otp) === intval(session('otp'))) {
            // Create the user

            User::create([
                'name' => session('signup_first_name') . ' ' . session('signup_last_name'), // Combine first and last name
                'email' => session('signup_email'),
                'password' => bcrypt(session('signup_password')),
                'account_type' => session('account_type'), // Include account type
            ]);


            // Clear session data
            session()->forget(['signup_email', 'signup_first_name', 'signup_last_name', 'otp', 'account_type']); // Clear OTP as well

            return response()->json(['message' => 'Signup successful!'], 201);
        }

        return response()->json(['message' => 'Invalid OTP.'], 400);
    }

    public function index()
    {
        return view('login'); // Adjust the view name as necessary
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Add remember me functionality
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out

        // Optionally invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token to prevent session fixation
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect to the login page or any other page you prefer
    }
}
