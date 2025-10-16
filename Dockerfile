FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Use SQLite for deployment
RUN touch database/database.sqlite

RUN composer install --no-dev --optimize-autoloader

# Generate app key and run migrations
RUN php artisan key:generate
RUN php artisan migrate --force

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]