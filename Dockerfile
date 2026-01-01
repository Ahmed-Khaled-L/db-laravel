FROM php:8.2-apache

# 1. Install system dependencies (including zip libraries)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip

# 4. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 5. Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Fix for "Could not reliably determine the server's fully qualified domain name"
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 6. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 8. Set working directory
WORKDIR /var/www/html

# 9. Copy project files
COPY . .

# 10. Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev


# 12. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 13. Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/

# === FIX FOR WINDOWS USERS ===
# This command removes the invisible Windows "Carriage Return" characters
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

# Make it executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
