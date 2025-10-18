#!/bin/bash

set -e  # Exit on any error

echo "Starting application setup..."

# Wait for database to be ready
echo "Waiting for database..."
sleep 10

# Generate application key if not set
if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$(grep '^APP_KEY=..*' .env)" ] || [ "$(grep '^APP_KEY=..*' .env)" = "APP_KEY=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Start services
echo "Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g 'daemon off;'