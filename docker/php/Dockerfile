FROM composer
FROM php:7.2-cli

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /usr/src/app

RUN apt-get update \
    && apt-get install -y git zip unzip

# install mysql ext
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets bcmath
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME /var/composer
RUN mkdir /var/composer

RUN composer global require "hirak/prestissimo" --prefer-dist
RUN chmod -R ugo+rw /var/composer

RUN php --version
CMD ["composer", "install"]
