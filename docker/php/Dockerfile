FROM php:8.3-fpm

# Install package yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Working directory
WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]