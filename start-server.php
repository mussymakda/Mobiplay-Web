#!/usr/bin/env php
<?php

/**
 * Enhanced Laravel Development Server with Increased Timeouts
 *
 * This script starts the Laravel development server with optimized settings
 * for API testing and longer request handling.
 */
echo "🚀 Starting Laravel Development Server with Enhanced Timeouts...\n";
echo "========================================================\n\n";

// Set PHP configuration for longer execution
ini_set('max_execution_time', 300);  // 5 minutes
ini_set('memory_limit', '512M');
ini_set('default_socket_timeout', 120);

// Server configuration
$host = '127.0.0.1';
$port = 8000;
$workers = 4;

// Check if port is available
$connection = @fsockopen($host, $port, $errno, $errstr, 1);
if ($connection) {
    fclose($connection);
    echo "⚠️  Port {$port} is already in use. Trying port 8001...\n";
    $port = 8001;
}

echo "Configuration:\n";
echo "- Host: {$host}\n";
echo "- Port: {$port}\n";
echo "- Workers: {$workers}\n";
echo "- Max Execution Time: 300 seconds\n";
echo "- Memory Limit: 512M\n";
echo "- Socket Timeout: 120 seconds\n\n";

// Set environment variables for the server
putenv("PHP_CLI_SERVER_WORKERS={$workers}");

// Build the command
$command = sprintf(
    'php -S %s:%d -t public public/index.php',
    $host,
    $port
);

echo "Starting server...\n";
echo "URL: http://{$host}:{$port}\n";
echo "API Base: http://{$host}:{$port}/api\n\n";

echo "Press Ctrl+C to stop the server\n";
echo str_repeat('-', 50)."\n\n";

// Start the server
passthru($command);
