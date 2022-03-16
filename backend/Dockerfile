FROM php:7-fpm-alpine

RUN apk add --no-cache autoconf gcc make automake g++ make libffi-dev openssl-dev

RUN docker-php-ext-install bcmath && docker-php-ext-enable bcmath

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN docker-php-ext-install pdo && docker-php-ext-enable pdo

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /tmp/*

WORKDIR /var/www/html

