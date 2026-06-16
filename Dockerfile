# Use official PHP Apache base image
FROM php:8.1-apache

# Install mysqli extension since the project uses native mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite for directory URL control
RUN a2enmod rewrite

# Copy all application files to the container web root
COPY . /var/www/html/

# Set working directory inside container
WORKDIR /var/www/html/

# Configure permissions for the www-data group to support uploads (e.g. settings images, products)
RUN chown -R www-data:www-data /var/www/html

# Expose standard Apache port
EXPOSE 80
