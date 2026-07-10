FROM php:8.2-apache

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libssl-dev \
    pkg-config

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Enable Apache Prefork MPM
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Copy project files
COPY . /var/www/html/

CMD ["bash", "-c", "a2dismod mpm_event || true && a2dismod mpm_worker || true && a2enmod mpm_prefork && exec apache2-foreground"]

EXPOSE 80