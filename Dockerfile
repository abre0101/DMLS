FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip sqlite3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# COMPLETELY remove and recreate the database
RUN rm -f database/database.sqlite && touch database/database.sqlite

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Fresh start every deployment
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000