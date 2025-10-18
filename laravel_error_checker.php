<?php
/**
 * Simple Laravel Error Checker
 * Upload this to your public directory and access via browser
 * This will help identify the exact 500 error cause
 */

echo "<h1>Laravel 500 Error Diagnostics</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .error{color:red;} .success{color:green;} .warning{color:orange;}</style>";

// Check if we're in the right directory
echo "<h2>1. Directory Check</h2>";
if (file_exists('../.env')) {
    echo "<span class='success'>✓ .env file found</span><br>";
} else {
    echo "<span class='error'>✗ .env file NOT found in parent directory</span><br>";
}

if (file_exists('../vendor/autoload.php')) {
    echo "<span class='success'>✓ Composer vendor directory found</span><br>";
} else {
    echo "<span class='error'>✗ Composer vendor directory NOT found</span><br>";
    echo "<p>Run: composer install --no-dev</p>";
}

if (file_exists('../bootstrap/app.php')) {
    echo "<span class='success'>✓ Laravel bootstrap file found</span><br>";
} else {
    echo "<span class='error'>✗ Laravel bootstrap file NOT found</span><br>";
}

// Check basic PHP requirements
echo "<h2>2. PHP Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";

// Try to load Laravel
echo "<h2>3. Laravel Bootstrap Test</h2>";
try {
    if (file_exists('../vendor/autoload.php')) {
        require '../vendor/autoload.php';
        echo "<span class='success'>✓ Composer autoloader loaded</span><br>";
        
        if (file_exists('../bootstrap/app.php')) {
            $app = require_once '../bootstrap/app.php';
            echo "<span class='success'>✓ Laravel application bootstrapped</span><br>";
            
            // Try to get config
            try {
                // This will trigger the .env loading
                $config = $app->make('config');
                echo "<span class='success'>✓ Configuration loaded</span><br>";
                
                // Check database config
                echo "<h3>Database Configuration:</h3>";
                $dbConnection = $config->get('database.default');
                echo "Default Connection: {$dbConnection}<br>";
                
                $dbConfig = $config->get("database.connections.{$dbConnection}");
                if ($dbConfig) {
                    echo "Host: " . ($dbConfig['host'] ?? 'not set') . "<br>";
                    echo "Database: " . ($dbConfig['database'] ?? 'not set') . "<br>";
                    echo "Username: " . ($dbConfig['username'] ?? 'not set') . "<br>";
                    echo "Password: " . (empty($dbConfig['password']) ? '<span class="error">EMPTY!</span>' : '<span class="success">Set</span>') . "<br>";
                } else {
                    echo "<span class='error'>Database configuration not found!</span><br>";
                }
                
            } catch (Exception $e) {
                echo "<span class='error'>✗ Configuration error: " . $e->getMessage() . "</span><br>";
            }
            
        } else {
            echo "<span class='error'>✗ Bootstrap file not found</span><br>";
        }
    } else {
        echo "<span class='error'>✗ Autoloader not found</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>✗ Critical error: " . $e->getMessage() . "</span><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Check .env contents (safely)
echo "<h2>4. Environment File Check</h2>";
if (file_exists('../.env')) {
    $env = file_get_contents('../.env');
    $lines = explode("\n", $env);
    
    echo "<h3>Database Settings:</h3>";
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, 'DB_') === 0) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = $parts[0];
                $value = $parts[1];
                if ($key === 'DB_PASSWORD') {
                    $value = empty($value) ? '<span class="error">EMPTY</span>' : '***';
                }
                echo "{$key} = {$value}<br>";
            }
        }
    }
} else {
    echo "<span class='error'>.env file not found!</span><br>";
}

echo "<h2>5. Recommended Actions</h2>";
echo "<ol>";
echo "<li>If database password is empty, update your .env file</li>";
echo "<li>Run: php artisan config:clear</li>";
echo "<li>Run: php artisan cache:clear</li>";
echo "<li>Check cPanel error logs</li>";
echo "<li>Make sure database user has proper privileges</li>";
echo "</ol>";

echo "<p><strong>Remember to delete this file after debugging!</strong></p>";
?>