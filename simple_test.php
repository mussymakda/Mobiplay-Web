<?php
// Simple test file that doesn't depend on Laravel
// Upload this as test.php to your public directory

echo "<h1>Server Test</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Time: " . date('Y-m-d H:i:s') . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Path: " . __FILE__ . "<br>";

echo "<h2>Directory Contents:</h2>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo $file . "<br>";
    }
}

echo "<h2>Environment Variables:</h2>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "<br>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "<br>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "<br>";

echo "<h2>.htaccess Check:</h2>";
if (file_exists('.htaccess')) {
    echo "✓ .htaccess file exists<br>";
    echo "Size: " . filesize('.htaccess') . " bytes<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms('.htaccess')), -4) . "<br>";
} else {
    echo "✗ .htaccess file not found<br>";
}

if (file_exists('../.env')) {
    echo "✓ .env file exists in parent directory<br>";
} else {
    echo "✗ .env file not found in parent directory<br>";
}
?>