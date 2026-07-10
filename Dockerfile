FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

COPY . /var/www/html/



CMD ["bash", "-c", "a2dismod mpm_event || true && a2dismod mpm_worker || true && a2enmod mpm_prefork && exec apache2-foreground"]

EXPOSE 80