FROM php:8.2-cli

WORKDIR /app

COPY . .

# Install MySQL client and wait-for-it script
RUN apt-get update && apt-get install -y \
    unzip \
    default-mysql-client \
    netcat \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Wait for database to be ready, then run migrations
CMD ["sh", "-c", "until nc -z $DB_HOST $DB_PORT; do echo 'Waiting for MySQL...'; sleep 2; done && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]