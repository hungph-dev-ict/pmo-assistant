# Sử dụng image PHP + Nginx
FROM php:8.2-fpm

# Cài đặt các extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài đặt thư mục làm việc
WORKDIR /var/www

# Copy composer.json và composer.lock
COPY composer.json composer.lock ./ 

# Cài đặt các dependency của ứng dụng
RUN composer install --optimize-autoloader --no-scripts

# Copy toàn bộ mã nguồn
COPY . .

RUN npm install
RUN npm run build

# Cấp quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy file cấu hình Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Railway sẽ tự động ánh xạ cổng được gán thông qua biến môi trường PORT
EXPOSE 8080
