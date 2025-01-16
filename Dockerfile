# Sử dụng image PHP với hỗ trợ Composer
FROM php:8.2-fpm

# Cài đặt các extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài đặt thư mục làm việc
WORKDIR /var/www

# Copy toàn bộ mã nguồn của ứng dụng
COPY . /var/www

# Phân quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Khai báo script entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Mở cổng cho ứng dụng
EXPOSE 8000
