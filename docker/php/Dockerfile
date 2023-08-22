FROM php:8.2-fpm-alpine

RUN apk add --no-cache openssl bash git mysql-client libzip-dev libpng-dev jpeg-dev linux-headers
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql bcmath gd

# Install Node.js and npm
RUN apk add --update nodejs npm

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

COPY 90-xdebug.ini "${PHP_INI_DIR}/conf.d"

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod a+x /usr/local/bin/composer

EXPOSE 9000

ENTRYPOINT ["php-fpm"]
