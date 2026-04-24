#!/bin/bash

# Deployment script for Dokkan Laravel Application (Docker Environment)
# This script automates the update process from GitHub to VPS

echo "🚀 Starting deployment process..."

# 1. Update Code from GitHub
echo "📥 Pulling latest changes from GitHub..."
git fetch --all
git reset --hard origin/main
git clean -fd

# 2. Fix Permissions (Host side)
echo "🔐 Setting folder permissions..."
chmod -R 777 storage bootstrap/cache vendor

# 3. Handle broken vendor (Check if vendor is missing key files)
if [ ! -d "vendor/laravel" ]; then
    echo "⚠️ Vendor folder seems broken. Cleaning up..."
    rm -rf vendor
fi

# 4. Install Dependencies
echo "📦 Installing composer dependencies..."
docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# 5. Run Database Migrations
echo "🗄️ Running database migrations..."
docker compose exec -T app php artisan migrate:fresh --seed --force

# 6. Clear Cache and Optimize
echo "⚡ Clearing cache and optimizing..."
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache

# 7. Handle Storage Link
echo "🔗 Verifying storage link..."
docker compose exec -T app php artisan storage:link || echo "Storage link already exists or failed."

echo "✅ Deployment completed successfully!"
