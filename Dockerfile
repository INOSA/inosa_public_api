FROM php:8.0.11-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    rm -rf /tmp/pear

COPY ./ /var/www/html

ARG HOST_IP
ARG ENV
ENV ENV ${ENV:-prod}

COPY ./docker/php/php.ini-${ENV} ${PHP_INI_DIR}/php.ini
COPY ./docker/php/opcache.ini-${ENV} ${PHP_INI_DIR}/opcache.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer creates=/usr/local/bin/composer warn=no

RUN apk add --no-cache nginx
COPY ./docker/nginx/publicapi.conf /etc/nginx/http.d/publicapi.conf
RUN rm /etc/nginx/http.d/default.conf

ADD https://github.com/just-containers/s6-overlay/releases/download/v1.21.8.0/s6-overlay-amd64.tar.gz /tmp/
RUN gunzip -c /tmp/s6-overlay-amd64.tar.gz | tar -xf - -C /

COPY ./docker/services.d/ /etc/services.d/

ENTRYPOINT ["/init"]
CMD ["php-fpm", "-F"]
