FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install -j$(nproc) intl zip pgsql pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID=1000
RUN usermod -u ${USER_ID} www-data && groupmod -g ${USER_ID} www-data

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

USER www-data