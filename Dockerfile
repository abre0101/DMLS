FROM php:8.2-fpm

WORKDIR /var/www

# Install only essential packages
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring gd \
    && apt-get clean

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . .

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Install dependencies - skip ALL scripts and plugins
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-plugins

EXPOSE 8000

# Simple start command
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000"]