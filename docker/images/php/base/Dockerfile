FROM php:8.1-fpm-alpine as base

ARG APP_USER_ID
ARG APP_GROUP_ID
ARG APP_USER_NAME
ARG APP_GROUP_NAME
ARG APP_CODE_PATH

ENV APP_USER_ID=${APP_USER_ID}
ENV APP_GROUP_ID=${APP_GROUP_ID}
ENV APP_USER_NAME=${APP_USER_NAME}
ENV APP_GROUP_NAME=${APP_GROUP_NAME}
ENV APP_CODE_PATH=${APP_CODE_PATH}


RUN addgroup -g $APP_GROUP_ID $APP_GROUP_NAME && \
    adduser -D -u $APP_USER_ID -s /bin/bash $APP_USER_NAME -G $APP_GROUP_NAME && \
    mkdir -p $APP_CODE_PATH && \
    chown $APP_USER_NAME: $APP_CODE_PATH

RUN apk add --no-cache --update autoconf g++ \
    openssl bash mysql-client redis make strace sudo vim &&\
    pecl install -f xdebug && \
    docker-php-ext-enable xdebug && \
    apk del --purge autoconf g++

RUN docker-php-ext-install pdo pdo_mysql bcmath

RUN rm -rf /var/www/html

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# make bash default shell
RUN sed -e 's;/bin/ash$;/bin/bash;g' -i /etc/passwd


COPY ./images/php/base/conf.d/ /usr/local/etc/php/conf.d/
COPY ./images/php/base/xdebug.ini /usr/local/etc/php/conf.d
COPY ./images/php/base/.bashrc /home/${APP_USER_NAME}/.bashrc
COPY ./images/php/base/.bashrc /root/.bashrc

WORKDIR $APP_CODE_PATH


