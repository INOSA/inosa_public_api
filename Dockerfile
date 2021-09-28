FROM php:8.0.11-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    rm -rf /tmp/pear

COPY docker/php /var/www/html

ARG HOST_IP
ARG ENV
ENV ENV ${ENV:-prod}

COPY ./docker/php/php.ini-${ENV} ${PHP_INI_DIR}/php.ini
COPY ./docker/php/opcache.ini-${ENV} ${PHP_INI_DIR}/opcache.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer creates=/usr/local/bin/composer warn=no

#NGINX
RUN apk add --no-cache nginx

COPY ./docker/nginx/publicapi.conf /etc/nginx/conf.d/publicapi.conf
