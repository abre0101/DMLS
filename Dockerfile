FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

# Generate key and start server
CMD ["sh", "-c", "php artisan key:generate && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=8000"]