<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>EMERGENCY 500 ERROR DEBUG</h1>";
echo "<pre>";

// 1. Basic PHP test
echo "=== BASIC PHP TEST ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Time: " . date("Y-m-d H:i:s") . "\n";
echo "Current Directory: " . getcwd() . "\n";

// 2. File system check
echo "\n=== FILE SYSTEM CHECK ===\n";
$critical_files = [
    "index.php",
    "public/index.php",
    ".env",
    "vendor/autoload.php",
    "bootstrap/app.php",
    "artisan",
    "create-user.php"
];

foreach ($critical_files as $file) {
    if (file_exists($file)) {
        echo "✓ $file EXISTS\n";
    } else {
        echo "✗ $file MISSING\n";
    }
}

// 3. Directory permissions
echo "\n=== DIRECTORY PERMISSIONS ===\n";
$dirs = ["storage", "storage/logs", "bootstrap/cache", "storage/framework"];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? "WRITABLE" : "NOT WRITABLE";
        echo "$dir: $perms ($writable)\n";
    } else {
        echo "$dir: MISSING\n";
    }
}

// 4. Try to load autoloader
echo "\n=== COMPOSER AUTOLOADER TEST ===\n";
try {
    if (file_exists("vendor/autoload.php")) {
        require_once "vendor/autoload.php";
        echo "✓ Autoloader loaded successfully\n";
    } else {
        echo "✗ vendor/autoload.php not found\n";
    }
} catch (Exception $e) {
    echo "✗ Autoloader error: " . $e->getMessage() . "\n";
}

// 5. Try to load Laravel
echo "\n=== LARAVEL BOOTSTRAP TEST ===\n";
try {
    if (file_exists("bootstrap/app.php")) {
        $app = require_once "bootstrap/app.php";
        echo "✓ Laravel app created\n";
        
        // Try to boot Laravel
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "✓ HTTP Kernel created\n";
        
    } else {
        echo "✗ bootstrap/app.php not found\n";
    }
} catch (Exception $e) {
    echo "✗ Laravel bootstrap error: " . $e->getMessage() . "\n";
    echo "Error in file: " . $e->getFile() . " line " . $e->getLine() . "\n";
}

// 6. Environment check
echo "\n=== ENVIRONMENT CHECK ===\n";
if (file_exists(".env")) {
    echo "✓ .env file exists\n";
    $env_content = file_get_contents(".env");
    if (strpos($env_content, "APP_KEY=") !== false) {
        echo "✓ APP_KEY found\n";
    } else {
        echo "✗ APP_KEY missing\n";
    }
    if (strpos($env_content, "DB_DATABASE=") !== false) {
        echo "✓ Database config found\n";
    } else {
        echo "✗ Database config missing\n";
    }
} else {
    echo "✗ .env file missing\n";
}

// 7. PHP Extensions check
echo "\n=== PHP EXTENSIONS CHECK ===\n";
$required = ["pdo", "pdo_mysql", "mbstring", "openssl", "tokenizer", "xml", "ctype", "json"];
foreach ($required as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ $ext loaded\n";
    } else {
        echo "✗ $ext MISSING\n";
    }
}

// 8. Check recent error logs
echo "\n=== RECENT ERROR LOGS ===\n";
$log_files = ["storage/logs/laravel.log", "error_log"];
foreach ($log_files as $log_file) {
    if (file_exists($log_file)) {
        echo "\n--- $log_file (last 5 lines) ---\n";
        $lines = file($log_file);
        $recent_lines = array_slice($lines, -5);
        foreach ($recent_lines as $line) {
            echo htmlspecialchars($line);
        }
    }
}

echo "\n\n=== DEBUG COMPLETE ===\n";
echo "</pre>";
?>