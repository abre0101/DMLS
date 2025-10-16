FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip sqlite3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Remove existing database and create fresh one
RUN rm -f database/database.sqlite && touch database/database.sqlite

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Use migrate:fresh without --force
CMD ["sh", "-c", "php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=10000"]