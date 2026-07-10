FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN apache2ctl -M

COPY . /var/www/html/

EXPOSE 80