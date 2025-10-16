FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y unzip sqlite3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN touch database/database.sqlite
RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Run migrations (continue even if some fail)
CMD ["sh", "-c", "php artisan migrate --force || true && php artisan serve --host=0.0.0.0 --port=10000"]