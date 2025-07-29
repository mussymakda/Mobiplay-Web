<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Testing User Profile Fields:\n";

// Get a test user
$user = User::first();

if ($user) {
    echo "User: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Type (Account Type): " . ($user->type ?? 'Not set') . "\n";
    echo "Phone: " . ($user->phone_number ?? 'Not set') . "\n";
    echo "Address: " . ($user->address_line1 ?? 'Not set') . "\n";
    echo "City: " . ($user->city ?? 'Not set') . "\n";
    echo "Profile Image URL: " . $user->profile_image_url . "\n";
    
    // Test fillable fields
    echo "\nFillable fields include new fields: ";
    $fillable = $user->getFillable();
    $newFields = ['phone_number', 'address_line1', 'address_line2', 'city', 'state_province', 'postal_code', 'country', 'profile_image'];
    $allIncluded = true;
    foreach ($newFields as $field) {
        if (!in_array($field, $fillable)) {
            echo "\nMissing: $field";
            $allIncluded = false;
        }
    }
    
    if ($allIncluded) {
        echo "YES - All fields are fillable\n";
    }
} else {
    echo "No users found in database\n";
}

echo "\nTest completed!\n";
