<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Ensure User model is imported

class RegistrationController extends Controller
{
    public function showSignupForm()
    {
        return view('signup');
    }

    public function handleSignup(Request $request)
    {
        // Validate and store initial signup data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Store initial data in session
        $request->session()->put('signup', $validated);

        // Redirect to next step
        return redirect()->route('signup.organization.form');
    }

    public function showOrganizationInfoForm()
    {
        return view('organization-info');
    }

    public function handleOrganizationInfo(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'organization_email' => 'required|string|email|max:255',
            'organization_phone' => 'required|string|max:15',
            'organization_address' => 'required|string|max:255',
        ]);

        // Store data in session
        $request->session()->put('organization_info', $validated);

        // Redirect to next step
        return redirect()->route('signup.phone.form');
    }

    public function showPhoneVerificationForm()
    {
        return view('phone-verification');
    }

    public function handlePhoneVerification(Request $request)
    {
        $validated = $request->validate([
            'verification_code' => 'required|string|max:6',
        ]);

        // Store phone verification data in session
        // $request->session()->put('phone_verification', $validated['verification_code']);

        // Redirect to the location form page
        return redirect()->route('signup.location.form');
    }

    public function showLocationForm()
    {
        return view('location');
    }

    public function handleLocation(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state_province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);

        // Store data in session
        $request->session()->put('location_info', $validated);

        // Redirect to next step
        return redirect()->route('signup.account.form');
    }

    public function showAccountForm()
    {
        return view('account');
    }

    public function handleAccount(Request $request)
    {
        // Debug session data
        $signupData = $request->session()->get('signup');
        $organizationInfo = $request->session()->get('organization_info');
        $locationInfo = $request->session()->get('location_info');
    
        // Validate final form data
        $validated = $request->validate([
            'account_type' => 'required|string',
        ]);
    
        // Check if all necessary data is available
        if (!$signupData || !$organizationInfo || !$locationInfo) {
            return redirect()->back()->withErrors(['error' => 'Incomplete signup data.']);
        }
    
        try {
            // Create the user
            $user = User::create([
                'name' => $signupData['name'],
                'email' => $signupData['email'],
                'password' => bcrypt($signupData['password']), // Use password from session
                'phone_number' => $organizationInfo['organization_phone'],
                'address_line1' => $locationInfo['address_line1'],
                'address_line2' => $locationInfo['address_line2'],
                'city' => $locationInfo['city'],
                'state_province' => $locationInfo['state_province'],
                'postal_code' => $locationInfo['postal_code'],
                'country' => $locationInfo['country'],
                'account_type' => $validated['account_type'],
            ]);
            // Clear session data
            $request->session()->flush();
    
            return redirect()->route('login');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating your account. Please try again.']);
        }
    }
    
}
