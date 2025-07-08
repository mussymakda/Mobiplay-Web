<?php
// Create the user directly
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['email' => 'mustansir.makda@gmail.com'],
    [
        'name' => 'Mustansir Makda',
        'password' => Hash::make('password'),
        'email_verified_at' => now()
    ]
);

echo "User created: " . $user->name . " (" . $user->email . ")";
