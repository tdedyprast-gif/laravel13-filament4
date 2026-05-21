FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    linux-headers \
    nodejs \
    npm \
    oniguruma-dev \
    supervisor \
    unzip \
    zip \
  && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
  && docker-php-ext-configure gd --with-jpeg --with-webp \
  && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    zip \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del .build-deps \
  && rm -rf /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 laravel \
  && adduser -D -G laravel -u 1000 laravel

USER laravel

RUN git config --global --add safe.directory /var/www/html