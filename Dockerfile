# Use an official PHP image with Apache
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP files to the server
COPY . .

# Install PHP extensions (if needed)
RUN docker-php-ext-install pdo_mysql

# Expose port 80
EXPOSE 80