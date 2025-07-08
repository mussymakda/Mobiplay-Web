<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Assuming you're using the User model

class EditProfileController extends Controller
{
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Pass user data to the view
        return view('edit', [
            'name' => $user->name,
            'email' => $user->email,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
            'city' => $user->city,
            'state_province' => $user->state_province,
            'postal_code' => $user->postal_code,
            'country' => $user->country,
            'phone_number' => $user->phone_number, // Assuming you have a phone field in the User model
        ]);
    }

    public function update(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20', // Add validation for phone
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Update user information
        $user->name = $request->first_name . ' ' . $request->last_name; // Concatenate first and last names
        $user->email = $request->email;
        $user->phone_number = $request->phone; // Update phone number
        $user->address_line1 = $request->address_line1;
        $user->address_line2 = $request->address_line2;
        $user->city = $request->city;
        $user->state_province = $request->state_province;
        $user->postal_code = $request->postal_code;
        $user->country = $request->country;
    
        // Save changes
        $user->save();
    
        // Redirect back with a success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
    
    
}
