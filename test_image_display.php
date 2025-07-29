<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Testing Profile Image Display:\n";

// Get the first user
$user = User::first();

if ($user) {
    echo "User: " . $user->name . "\n";
    echo "Profile Image Field: " . ($user->profile_image ?? 'NULL') . "\n";
    
    // Test the accessor
    try {
        echo "Profile Image URL (accessor): " . $user->profile_image_url . "\n";
    } catch (Exception $e) {
        echo "Accessor Error: " . $e->getMessage() . "\n";
    }
    
    // Test manual URL generation
    if ($user->profile_image) {
        $manualUrl = asset('storage/' . $user->profile_image);
        echo "Manual URL: " . $manualUrl . "\n";
        
        // Check if file exists
        $filePath = storage_path('app/public/' . $user->profile_image);
        echo "File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
        echo "File path: " . $filePath . "\n";
    } else {
        $defaultUrl = asset('assets/images/demo-profile.svg');
        echo "Default URL: " . $defaultUrl . "\n";
    }
} else {
    echo "No users found\n";
}

echo "\nTest completed!\n";
