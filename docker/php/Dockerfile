FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    opcache \
    intl \
    zip \
    gd \
    bcmath

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN addgroup -g 1000 cms && \
    adduser -u 1000 -G cms -s /bin/bash -D cms && \
    chown -R cms:cms /var/www/html

USER cms

EXPOSE 9000
