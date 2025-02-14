FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache for UTF-8
RUN echo "AddDefaultCharset UTF-8" >> /etc/apache2/conf-available/charset.conf \
    && a2enconf charset

# Set PHP configuration for UTF-8
RUN echo "default_charset = UTF-8" >> /usr/local/etc/php/php.ini-development \
    && echo "default_charset = UTF-8" >> /usr/local/etc/php/php.ini-production \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html
