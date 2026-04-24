#!/bin/bash

# Deployment script for Dokkan Laravel Application (Docker Environment)
# This script ensures the entire environment is correctly initialized and updated

echo "🚀 Starting full deployment and initialization..."

# 1. Update Code from GitHub
echo "📥 Syncing code with GitHub..."
git fetch --all
git reset --hard origin/main
git clean -fd

# 2. Ensure Docker Containers are Running
echo "🐳 Checking Docker containers..."
docker compose up -d

# 3. Fix Permissions (Host & Container)
echo "🔐 Optimizing file permissions..."
chmod -R 777 storage bootstrap/cache vendor
docker compose exec -T app chmod -R 777 storage bootstrap/cache

# 4. Critical Dependencies Check
echo "📦 Managing dependencies..."
# If vendor is missing or broken, force a clean install
if [ ! -d "vendor/laravel" ]; then
    echo "⚠️ Vendor directory is missing or corrupt. Reinstalling..."
    rm -rf vendor
    docker compose exec -T app composer install --no-interaction --prefer-dist
else
    docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 5. Database Initialization
echo "🗄️ Initializing database..."
# Note: Using migrate for safety. 
# Run 'docker compose exec app php artisan migrate:fresh --seed' manually for full reset.
docker compose exec -T app php artisan migrate --force

# 6. Optimize Application
echo "⚡ Optimizing Laravel..."
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

# 7. Storage and Symbolic Links
echo "🔗 Verifying storage links..."
docker compose exec -T app php artisan storage:link --force || echo "Storage link handling completed."

echo "✨ Deployment and Environment initialization successful!"
