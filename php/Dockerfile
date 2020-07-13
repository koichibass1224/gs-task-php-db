FROM php:7.4-fpm
RUN apt-get update
RUN apt-get install -y vim

RUN docker-php-ext-install pdo_mysql

COPY php.ini /usr/local/etc/php/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
