FROM php:8.2-cli

WORKDIR /var/www

# Install only the absolute essentials
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring \
    && apt-get clean

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . .

# Install dependencies without any optimization
RUN composer install --no-dev --no-scripts --no-plugins

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000