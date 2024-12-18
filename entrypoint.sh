#!/bin/bash

# Wait for MySQL to be ready
while ! nc -z db 3306; do
  echo "Waiting for database connection..."
  sleep 2
done

# Run Laravel commands
echo "Running Laravel setup commands..."
php artisan key:generate --ansi
php artisan scribe:generate
php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM
exec php-fpm
