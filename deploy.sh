#!/bin/bash

# Deployment Script for AlKamelah.com
# Usage: ./deploy.sh

echo "🚀 Starting Deployment Process for AlKamelah..."

# 1. Install/Update Dependencies (optimize for prod)
echo "📦 Installing Composer Dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Clear & Cache Config
echo "🧹 Clearing & Caching Config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Migrate Database (Force is dangerous, verify before running in prod!)
echo "🗄️ Running Migrations..."
php artisan migrate --force

# 4. Generate Sitemap
echo "🗺️ Generating Sitemap..."
php artisan sitemap:generate

# 5. Optimize Clear (Just to be safe)
echo "✨ Optimizing..."
php artisan optimize

echo "✅ Deployment Completed Successfully!"
echo "🌍 Check your site at https://alkamelah.com"
