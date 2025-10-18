<?php
// BASIC PHP TEST - If this doesn't work, PHP itself is broken
echo "PHP is working!<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "PHP version: " . phpversion() . "<br>";
echo "Date: " . date('Y-m-d H:i:s') . "<br>";

if (function_exists('mysqli_connect')) {
    echo "MySQL support: Available<br>";
} else {
    echo "MySQL support: NOT AVAILABLE<br>";
}
?>