@echo off
echo Starting Laravel Server with Enhanced Timeouts...
echo ================================================

REM Set PHP configuration for better performance
set PHP_CLI_SERVER_WORKERS=4

REM Start the enhanced server
php start-server.php

pause