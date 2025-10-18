<?php
// EMERGENCY 500 ERROR DIAGNOSTIC SCRIPT
// Upload this to your server root and visit it directly

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>500 Error Diagnostic</h1>";

// Check if Laravel files exist
echo "<h2>File System Check</h2>";
$files_to_check = [
    'index.php',
    'public/index.php', 
    '.env',
    'vendor/autoload.php',
    'bootstrap/app.php',
    'artisan'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file MISSING<br>";
    }
}

// Check PHP version
echo "<h2>PHP Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

// Check if we can load Laravel
echo "<h2>Laravel Bootstrap Test</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "✅ Autoloader loaded successfully<br>";
        
        if (file_exists('bootstrap/app.php')) {
            $app = require_once 'bootstrap/app.php';
            echo "✅ Laravel app bootstrapped<br>";
        } else {
            echo "❌ bootstrap/app.php not found<br>";
        }
    } else {
        echo "❌ vendor/autoload.php not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error loading Laravel: " . $e->getMessage() . "<br>";
}

// Check .env file
echo "<h2>Environment File Check</h2>";
if (file_exists('.env')) {
    echo "✅ .env file exists<br>";
    $env_content = file_get_contents('.env');
    if (strpos($env_content, 'APP_KEY=') !== false) {
        echo "✅ APP_KEY found in .env<br>";
    } else {
        echo "❌ APP_KEY missing from .env<br>";
    }
    if (strpos($env_content, 'DB_DATABASE=') !== false) {
        echo "✅ Database config found<br>";
    } else {
        echo "❌ Database config missing<br>";
    }
} else {
    echo "❌ .env file missing<br>";
}

// Check permissions
echo "<h2>Directory Permissions</h2>";
$dirs_to_check = [
    'storage',
    'storage/logs',
    'storage/framework',
    'bootstrap/cache'
];

foreach ($dirs_to_check as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? '✅' : '❌';
        echo "$writable $dir (permissions: $perms)<br>";
    } else {
        echo "❌ $dir directory missing<br>";
    }
}

// Check for error logs
echo "<h2>Recent Error Logs</h2>";
$log_files = [
    'storage/logs/laravel.log',
    'error_log'
];

foreach ($log_files as $log_file) {
    if (file_exists($log_file)) {
        echo "<h3>$log_file (last 10 lines):</h3>";
        $lines = file($log_file);
        $last_lines = array_slice($lines, -10);
        echo "<pre>" . htmlspecialchars(implode('', $last_lines)) . "</pre>";
    }
}

echo "<hr>";
echo "Diagnostic complete. Check the results above to identify the issue.";
?>