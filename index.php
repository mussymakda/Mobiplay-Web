<?php
// Emergency root index.php - bypasses Laravel routing
echo "ROOT INDEX.PHP WORKING<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Time: " . date('Y-m-d H:i:s') . "<br>";

// Test if we can load Laravel
if (file_exists('vendor/autoload.php')) {
    echo "Autoloader exists<br>";
    try {
        require_once 'vendor/autoload.php';
        echo "Autoloader loaded<br>";
        
        if (file_exists('bootstrap/app.php')) {
            echo "Bootstrap exists<br>";
            $app = require_once 'bootstrap/app.php';
            echo "Laravel app created<br>";
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "<br>";
    }
} else {
    echo "No autoloader found<br>";
}

echo "<hr>";
echo "<a href='emergency_debug.php'>Run Full Debug</a><br>";
echo "<a href='test.php'>Basic PHP Test</a><br>";
echo "<a href='cleanup_server.php'>Cleanup Server</a><br>";
echo "<a href='public/'>Go to Laravel Public</a><br>";
?>