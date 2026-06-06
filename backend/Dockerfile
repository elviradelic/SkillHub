FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

RUN apt-get update \
    && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && a2enmod mpm_prefork rewrite headers

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN echo "DOCKERFILE_VERSION_FORCE_MPM_FIX_123"

EXPOSE 10000

CMD echo "Listen ${PORT:-10000}" > /etc/apache2/ports.conf \
    && sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT:-10000}>/" /etc/apache2/sites-available/000-default.conf \
    && apache2-foreground