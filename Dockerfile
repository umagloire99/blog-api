# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel application to the container
COPY . .

# Install Composer dependencies
RUN composer install

# Set permissions

# Expose port 9000 to be able to access the application
EXPOSE 9000

COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

CMD ["entrypoint"]
