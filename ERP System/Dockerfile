# Use an official PHP image with Apache
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# Copy application source code
COPY . /var/www/html/

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html/
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
