FROM php:8.1-apache

# Instalar dependencias necesarias para GD, PDO, MySQL, etc.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli gd soap zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar configuración personalizada de PHP si tenés
COPY php.ini /usr/local/etc/php/
