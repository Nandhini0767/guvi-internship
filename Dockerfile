FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork rewrite

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]