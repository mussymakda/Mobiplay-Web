<?php
/**
 * Database Configuration Helper
 * Upload this to your cPanel public_html directory temporarily
 * Delete after getting your database info for security
 */

echo "<h1>Database Configuration Helper</h1>";

// Check if .env exists
if (file_exists('.env')) {
    echo "<h2>Current .env Database Config:</h2>";
    $env = file_get_contents('.env');
    
    $patterns = [
        'DB_CONNECTION' => '/DB_CONNECTION=(.+)/',
        'DB_HOST' => '/DB_HOST=(.+)/',
        'DB_PORT' => '/DB_PORT=(.+)/',
        'DB_DATABASE' => '/DB_DATABASE=(.+)/',
        'DB_USERNAME' => '/DB_USERNAME=(.+)/',
        'DB_PASSWORD' => '/DB_PASSWORD=(.+)/'
    ];
    
    foreach ($patterns as $key => $pattern) {
        if (preg_match($pattern, $env, $matches)) {
            $value = trim($matches[1]);
            if ($key === 'DB_PASSWORD') {
                $value = empty($value) ? '<span style="color: red">EMPTY - THIS IS THE PROBLEM!</span>' : '***hidden***';
            }
            echo "{$key}: {$value}<br>";
        } else {
            echo "{$key}: <span style='color: red'>NOT SET</span><br>";
        }
    }
} else {
    echo "<span style='color: red'>.env file not found!</span><br>";
}

echo "<h2>Available MySQL Databases:</h2>";
echo "<p>Check your cPanel → MySQL Databases for:</p>";
echo "<ul>";
echo "<li>Database names (usually prefixed with your username)</li>";
echo "<li>Database users (usually prefixed with your username)</li>";
echo "<li>Make sure the user has privileges for the database</li>";
echo "</ul>";

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>Get your database credentials from cPanel</li>";
echo "<li>Update your .env file with correct values</li>";
echo "<li>Run: php artisan config:clear</li>";
echo "<li>Run: php artisan migrate</li>";
echo "<li><strong>DELETE THIS FILE for security!</strong></li>";
echo "</ol>";
?>