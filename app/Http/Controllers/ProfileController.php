<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Return the view with the user data
        return view('profile', [
            'name' => $user->name ?? '-',
            'email' => $user->email ?? '-',
            'balance' => $user->balance ?? '-',
            'phone_number' => $user->phone_number ?? '-',
            'address_line1' => $user->address_line1 ?? '-',
            'address_line2' => $user->address_line2 ?? '-',
            'city' => $user->city ?? '-',
            'state_province' => $user->state_province ?? '-',
            'postal_code' => $user->postal_code ?? '-',
            'country' => $user->country ?? '-',
            'account_type' => $user->account_type ?? '-',
        ]);
    }
}
