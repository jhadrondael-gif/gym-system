FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Exclude .env from image (use runtime env vars instead)
RUN rm -f /var/www/html/.env

EXPOSE 80