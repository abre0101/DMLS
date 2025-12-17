#!/bin/bash

set -e

echo "Starting application setup..."

# Create .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env file from .env.example"
fi

# Generate app key if not set
if ! grep -q '^APP_KEY=..*' .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run package discovery
echo "Running package discovery..."
php artisan package:discover --ansi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Create storage links if needed
echo "Creating storage links..."
php artisan storage:link || true

echo "Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g 'daemon off;'