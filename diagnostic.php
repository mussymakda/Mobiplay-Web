<?php
/**
 * Laravel Production Diagnostic Script
 * Upload this to your server and access it to diagnose issues
 * Remember to delete this file after troubleshooting for security
 */

echo "<h1>Laravel Production Diagnostics</h1>";

// Check PHP version
echo "<h2>PHP Configuration</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

// Check file permissions
echo "<h2>File Permissions</h2>";
$paths = [
    'storage' => '../storage',
    'bootstrap/cache' => '../bootstrap/cache',
    '.env' => '../.env',
    'vendor' => '../vendor'
];

foreach ($paths as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "{$name}: {$perms} (exists)<br>";
    } else {
        echo "{$name}: <span style='color: red'>NOT FOUND</span><br>";
    }
}

// Check Laravel bootstrap
echo "<h2>Laravel Bootstrap</h2>";
try {
    if (file_exists('../vendor/autoload.php')) {
        require '../vendor/autoload.php';
        echo "Composer autoload: <span style='color: green'>OK</span><br>";
        
        if (file_exists('../bootstrap/app.php')) {
            echo "Bootstrap file: <span style='color: green'>OK</span><br>";
            
            // Try to bootstrap Laravel
            try {
                $app = require_once '../bootstrap/app.php';
                echo "Laravel bootstrap: <span style='color: green'>OK</span><br>";
                
                // Check environment
                if (file_exists('../.env')) {
                    echo ".env file: <span style='color: green'>EXISTS</span><br>";
                } else {
                    echo ".env file: <span style='color: red'>MISSING</span><br>";
                }
                
            } catch (Exception $e) {
                echo "Laravel bootstrap: <span style='color: red'>FAILED</span><br>";
                echo "Error: " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Bootstrap file: <span style='color: red'>MISSING</span><br>";
        }
    } else {
        echo "Composer autoload: <span style='color: red'>MISSING</span><br>";
        echo "Run: composer install --no-dev<br>";
    }
} catch (Exception $e) {
    echo "Critical Error: " . $e->getMessage() . "<br>";
}

// Check database connectivity (if .env exists)
echo "<h2>Database Connection</h2>";
if (file_exists('../.env')) {
    $env = file_get_contents('../.env');
    if (preg_match('/DB_HOST=(.+)/', $env, $matches)) {
        $host = trim($matches[1]);
        if (preg_match('/DB_DATABASE=(.+)/', $env, $matches)) {
            $database = trim($matches[1]);
            if (preg_match('/DB_USERNAME=(.+)/', $env, $matches)) {
                $username = trim($matches[1]);
                if (preg_match('/DB_PASSWORD=(.+)/', $env, $matches)) {
                    $password = trim($matches[1]);
                    
                    try {
                        $pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
                        echo "Database connection: <span style='color: green'>OK</span><br>";
                    } catch (PDOException $e) {
                        echo "Database connection: <span style='color: red'>FAILED</span><br>";
                        echo "Error: " . $e->getMessage() . "<br>";
                    }
                }
            }
        }
    }
} else {
    echo "Cannot test database - .env file missing<br>";
}

// Show recent errors
echo "<h2>Recent PHP Errors</h2>";
if (function_exists('error_get_last')) {
    $error = error_get_last();
    if ($error) {
        echo "Last error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'] . "<br>";
    } else {
        echo "No recent PHP errors<br>";
    }
}

echo "<h2>Next Steps</h2>";
echo "1. Fix any red issues above<br>";
echo "2. Check cPanel error logs<br>";
echo "3. Check Laravel logs in storage/logs/<br>";
echo "4. Run the setup commands from the instructions<br>";
echo "<br><strong>Remember to delete this diagnostic file for security!</strong>";
?>