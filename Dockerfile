FROM php:8-cli-alpine

WORKDIR /opt/php-a-star

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json .

RUN composer install

COPY . .
