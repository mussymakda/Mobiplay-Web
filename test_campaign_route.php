<?php
require_once __DIR__ . '/vendor/autoload.php';

// Create Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Boot the application
    $app->boot();
    
    // Test if we can get user balance
    $user = App\Models\User::first();
    if ($user) {
        echo "User found: " . $user->name . "\n";
        echo "Balance: $" . number_format($user->total_balance, 2) . "\n";
        echo "Regular balance: $" . number_format($user->balance, 2) . "\n";
        echo "Bonus balance: $" . number_format($user->bonus_balance, 2) . "\n";
    } else {
        echo "No users found in database.\n";
    }
    
    // Test if Package model works
    $packages = App\Models\Package::active()->count();
    echo "Active packages: " . $packages . "\n";
    
    echo "Campaign route test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
