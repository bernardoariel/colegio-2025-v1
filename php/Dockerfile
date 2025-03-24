FROM php:5.6-apache

# Habilitar extensiones necesarias (pdo, mysqli, etc)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activar mod_rewrite si lo usás
RUN a2enmod rewrite

# Opcional: config personalizada
COPY php.ini /usr/local/etc/php/
