FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip sqlite3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create fresh database
RUN touch database/database.sqlite

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Skip migrations entirely for now - just start the server
CMD php artisan serve --host=0.0.0.0 --port=10000