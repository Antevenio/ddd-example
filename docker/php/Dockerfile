FROM php:7.2-cli

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /usr/src/app

RUN apt-get update \
    && apt-get install -y git zip unzip

# install mysql ext
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer config -g preferred-install dist \
    && rm -rf /usr/src/php

RUN php --version
CMD ["php", "-a"]
