#!/bin/bash

# Chờ database sẵn sàng
echo "Waiting for database connection..."
until nc -z -v -w30 db 3306; do
  echo "Waiting for database..."
  sleep 5
done

# Chạy migrate
echo "Running migrations..."
php artisan migrate --force

# Khởi động PHP-FPM
php-fpm
