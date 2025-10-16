FROM php:8.2-cli

WORKDIR /app

# Copy application files
COPY . .

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create SQLite database file
RUN touch database/database.sqlite

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Run database migrations
RUN php artisan migrate --force

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]