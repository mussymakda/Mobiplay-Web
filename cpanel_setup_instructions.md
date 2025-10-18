# cPanel Production Setup Instructions

## Option 1: Configure Document Root (Recommended)

### Method A: Using cPanel Subdomain/Domain Settings
1. Log into cPanel
2. Go to "Subdomains" or "Addon Domains"
3. Set the Document Root to: `/public_html/Mobiplay-Web/public`
4. This makes https://mobiplay.mx point directly to the Laravel public folder

### Method B: Using .htaccess Redirect (if you can't change document root)
Create/Edit the .htaccess file in your main public_html directory:

```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L,R=301]
```

## Option 2: Move Laravel to Root (Alternative)

If you need the Laravel app at the root level:

1. Move all Laravel files to public_html:
```bash
# From your Laravel project root
mv * ../public_html/
mv .* ../public_html/ 2>/dev/null || true
```

2. Update the paths in public_html/index.php:
```php
require __DIR__.'/vendor/autoload.php';
(require_once __DIR__.'/bootstrap/app.php')
```

## Step 2: Fix the 500 Error

After fixing document root, run these commands on the server:

```bash
# Navigate to your Laravel root directory
cd /path/to/your/laravel/app

# Fix the git diverging branches issue
git fetch origin
git reset --hard origin/main

# Install only production dependencies
composer install --no-dev --optimize-autoloader

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework

# Clear all caches
php artisan config:clear
php artisan route:clear  
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Generate application key if needed
php artisan key:generate

# Cache config for production (optional but recommended)
php artisan config:cache
php artisan route:cache
```

## Step 3: Environment Configuration

Make sure your .env file on the server has:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mobiplay.mx

# Database settings for production
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

## Step 4: Check Error Logs

If you still get 500 errors, check:
- cPanel Error Logs
- Laravel logs in storage/logs/laravel.log
- PHP error logs

Common 500 error causes:
1. Missing .env file
2. Wrong file permissions
3. Database connection issues
4. Missing composer dependencies
5. Cached files with wrong paths