FROM php:8.2-cli

WORKDIR /app

COPY . .

# Install only essential packages
RUN apt-get update && apt-get install -y \
    unzip \
    sqlite3 \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create SQLite database
RUN touch database/database.sqlite

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]