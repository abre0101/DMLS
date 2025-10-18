#!/bin/bash

set -e

echo "Starting application deployment..."

# Create .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example"
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q '^APP_KEY=..*' .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration (optional - can be removed if causing issues)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

echo "Starting server..."
php-fpm -D
nginx -g 'daemon off;'
