FROM php:7.2-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql sockets bcmath
RUN pecl install redis && docker-php-ext-enable redis

CMD ["php-fpm"]

EXPOSE 9000
