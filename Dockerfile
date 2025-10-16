FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip sqlite3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN rm -f database/database.sqlite && touch database/database.sqlite
RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# See migration status and detailed errors
CMD ["sh", "-c", "echo '=== Checking migrations ===' && php artisan migrate:status && echo '=== Running migrations ===' && php artisan migrate --force && echo '=== Starting server ===' && php artisan serve --host=0.0.0.0 --port=10000"]