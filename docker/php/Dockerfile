FROM php:8.3.7-fpm-alpine3.20

WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    bash \
    unzip \
    tzdata \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    openssl-dev \
    supervisor

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd intl mbstring pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./src /var/www/html

RUN chmod -R 775 storage

COPY ./docker/php/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/php/supervisord.d /etc/supervisord.d
