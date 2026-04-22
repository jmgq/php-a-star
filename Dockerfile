FROM php:8.0-cli-alpine

WORKDIR /opt/php-a-star

RUN echo 'memory_limit = 256M' >> $PHP_INI_DIR/conf.d/docker-php-memory-limit.ini

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json .

RUN composer install

COPY . .
