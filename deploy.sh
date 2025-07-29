#!/bin/bash

# JomCloud Laravel Deployment Script
# Usage: ./deploy.sh

echo "🚀 Starting Laravel deployment to JomCloud..."

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install/Update NPM dependencies and build assets
echo "🎨 Building frontend assets..."
npm install
npm run build

# Clear and cache configurations
echo "⚡ Optimizing Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your Laravel app is now live!"