FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libmcrypt-dev \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID=1000
RUN usermod -u ${USER_ID} www-data && groupmod -g ${USER_ID} www-data

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

USER www-data