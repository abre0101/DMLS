#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
sleep 10

# Generate key if not set
if [ -z "$(grep '^APP_KEY=..*' .env)" ] || [ "$(grep '^APP_KEY=..*' .env)" = "APP_KEY=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Start PHP-FPM and nginx
echo "Starting application server..."
php-fpm -D
nginx -g 'daemon off;'