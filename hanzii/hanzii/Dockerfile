FROM php:8.2-fpm

# Cài đặt các dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client

# Cài đặt các extension PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài đặt Node.js và npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Sao chép composer.json và composer.lock
COPY composer*.json ./

# Cài đặt dependencies của PHP
RUN composer install --no-scripts --no-autoloader

# Sao chép các file của dự án
COPY . /var/www

# Tạo autoloader và chạy scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

# Cài đặt dependencies của Node.js và build assets
RUN npm install && npm run build

# Cấp quyền cho storage và bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

# Chạy PHP-FPM
CMD ["php-fpm"] 