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
chmod -R 777 storage bootstrap/cache

# 3. Install Dependencies
echo "📦 Installing composer dependencies..."
docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Run Database Migrations
# Note: Using 'migrate' for safety. Use 'migrate:fresh --seed' manually if rebuilding from scratch.
echo "🗄️ Running database migrations..."
docker compose exec -T app php artisan migrate --force

# 5. Clear Cache and Optimize
echo "⚡ Clearing cache and optimizing..."
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache

# 6. Handle Storage Link
echo "🔗 Verifying storage link..."
docker compose exec -T app php artisan storage:link || echo "Storage link already exists or failed."

echo "✅ Deployment completed successfully!"
