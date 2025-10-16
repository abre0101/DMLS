FROM php:8.2-cli

WORKDIR /app

COPY . .

# Install MySQL extensions
RUN apt-get update && apt-get install -y \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000