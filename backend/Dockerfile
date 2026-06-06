FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

RUN apt-get update \
    && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

RUN a2enmod rewrite headers

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]