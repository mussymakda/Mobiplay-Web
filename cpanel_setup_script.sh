#!/bin/bash

# Laravel cPanel Production Setup Script
# Run this on your cPanel server to fix permissions and setup

echo "Starting Laravel cPanel setup..."

# Navigate to Laravel root
cd /home/mobiplay/public_html

echo "Creating required directories..."
mkdir -p bootstrap/cache
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions  
mkdir -p storage/framework/views
mkdir -p storage/logs

echo "Setting permissions..."
chmod -R 755 bootstrap
chmod -R 755 bootstrap/cache
chmod -R 755 storage
chmod -R 775 storage/logs
chmod -R 775 storage/framework

echo "Setting ownership..."
chown -R mobiplay:mobiplay bootstrap/cache
chown -R mobiplay:mobiplay storage

echo "Clearing composer cache..."
composer clear-cache

echo "Installing dependencies..."
rm -rf vendor/
composer install --no-dev --optimize-autoloader --no-interaction

echo "Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage link..."
php artisan storage:link

echo "Testing setup..."
php artisan --version

echo "Setup complete! Check for any errors above."
echo "If successful, your Laravel app should now work at https://mobiplay.mx/public/"