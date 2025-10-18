FROM php:8.2-fpm

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only necessary files first
COPY composer.json composer.lock ./

# Install dependencies (no autoloader optimization yet)
RUN composer install --no-dev --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Now run composer scripts
RUN composer dump-autoload --optimize

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Create .env from example if it doesn't exist (without running artisan)
RUN if [ ! -f .env ]; then \
        cp .env.example .env; \
        echo "APP_KEY=" >> .env; \
    fi

EXPOSE 8000

# Start script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]