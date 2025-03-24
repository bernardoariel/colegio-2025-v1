FROM php:5.6-apache

# Eliminar repos stretch-updates que ya no existen
RUN sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list && \
    apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install pdo pdo_mysql mysqli gd

# Activar mod_rewrite
RUN a2enmod rewrite

# Copiar php.ini personalizado (si lo tenés)
COPY php.ini /usr/local/etc/php/
