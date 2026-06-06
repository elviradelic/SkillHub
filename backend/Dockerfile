FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

RUN apt-get update \
    && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

RUN a2enmod rewrite headers

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

CMD sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf \
    && sed -i "s/:80>/:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf \
    && apache2-foreground