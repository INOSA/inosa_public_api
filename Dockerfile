FROM php:8.0.13-fpm-alpine3.13

ARG HOST_IP
ARG ENV
ENV ENV ${ENV:-prod}
ARG MSSQL_VERSION=17.8.1.1-1
ENV MSSQL_VERSION=${MSSQL_VERSION}

RUN apk add --no-cache \
    unixodbc-dev \
    gnutls gnutls-utils \
    $PHPIZE_DEPS \
    && docker-php-ext-install opcache pdo \
    && docker-php-ext-enable opcache pdo

RUN apk add --no-cache curl gnupg --virtual .build-dependencies -- && \
    # Adding custom MS repository for mssql-tools and msodbcsql
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_${MSSQL_VERSION}_amd64.apk && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_${MSSQL_VERSION}_amd64.apk && \
    # Verifying signature
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_${MSSQL_VERSION}_amd64.sig && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_${MSSQL_VERSION}_amd64.sig && \
    # Importing gpg key
    curl https://packages.microsoft.com/keys/microsoft.asc  | gpg --import - && \
    gpg --verify msodbcsql17_${MSSQL_VERSION}_amd64.sig msodbcsql17_${MSSQL_VERSION}_amd64.apk && \
    gpg --verify mssql-tools_${MSSQL_VERSION}_amd64.sig mssql-tools_${MSSQL_VERSION}_amd64.apk && \
    # Installing packages
    echo y | apk add --allow-untrusted msodbcsql17_${MSSQL_VERSION}_amd64.apk mssql-tools_${MSSQL_VERSION}_amd64.apk && \
    # Deleting packages
    apk del .build-dependencies && rm -f msodbcsql*.sig mssql-tools*.apk

RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

COPY ./ /var/www/html

COPY ./docker/php/php.ini-${ENV} ${PHP_INI_DIR}/php.ini
COPY ./docker/php/opcache.ini-${ENV} ${PHP_INI_DIR}/opcache.ini

RUN set -eux; if [[ ${ENV} == "dev" ]]; then \
        pecl install xdebug \
        && docker-php-ext-enable --ini-name xdebug.ini xdebug \
    ;fi

COPY ./docker/php/xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer creates=/usr/local/bin/composer warn=no

RUN mkdir -p /run/nginx
RUN apk add --no-cache nginx
COPY ./docker/nginx/publicapi.conf /etc/nginx/http.d/publicapi.conf
RUN rm /etc/nginx/http.d/default.conf

ADD https://github.com/just-containers/s6-overlay/releases/download/v1.21.8.0/s6-overlay-amd64.tar.gz /tmp/
RUN gunzip -c /tmp/s6-overlay-amd64.tar.gz | tar -xf - -C /
ENV S6_BEHAVIOUR_IF_STAGE2_FAILS 2

COPY ./docker/services.d/ /etc/services.d/
COPY ./docker/cont-init.d/ /etc/cont-init.d/

ENTRYPOINT ["/init"]
CMD ["php-fpm", "-F"]
