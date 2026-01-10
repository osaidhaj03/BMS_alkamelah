#!/bin/bash
# Fix Composer Deployment Issue
# This script fixes the "Trying to access array offset on null" error

echo "🔧 Fixing Composer deployment issue..."

# Navigate to project directory
cd /www/wwwroot/alkamelah1.anwaralolmaa.com || exit 1

# Step 1: Reset composer.lock from Git
echo "📝 Resetting composer.lock from Git..."
git checkout composer.lock

# Step 2: Clear composer cache
echo "🧹 Clearing composer cache..."
composer clear-cache

# Step 3: Remove vendor directory (optional but recommended)
echo "🗑️ Removing vendor directory..."
rm -rf vendor/

# Step 4: Fresh install
echo "📦 Running fresh composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Step 5: Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Step 6: Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 7: Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www:www storage bootstrap/cache

echo "✅ Deployment fix completed!"
echo "🚀 You can now re-run your deployment process."
