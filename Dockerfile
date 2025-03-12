# Use an official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite and allow .htaccess overrides
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install PHP extensions (mysqli and pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP files to the server
COPY . .

# Expose port 80
EXPOSE 80