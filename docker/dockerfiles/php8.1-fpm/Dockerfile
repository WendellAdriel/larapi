FROM php:8.1-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

# INSTALL LINUX PACKAGES
RUN apt-get update -y && \
    apt-get install -y \
        vim \
        zip \
        git \
        unzip \
        pkg-config \
        uuid-runtime \
        libpng-dev \
        libssl-dev \
        libxml2-dev \
        libzip-dev \
        libnss3-tools \
        libcurl4-openssl-dev

# INSTALL EXTENSIONS
RUN	docker-php-ext-install pdo_mysql mysqli pcntl soap gd

# INSTALL ZIP EXTENSION
RUN	docker-php-ext-install zip

RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html/app

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
