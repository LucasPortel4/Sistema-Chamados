FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Mantem o container ativo para desenvolvimento com bind mount.
CMD ["php-fpm"]

