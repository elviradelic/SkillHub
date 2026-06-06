FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

RUN apt-get update \
    && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql mysqli zip

RUN a2dismod -f mpm_event mpm_worker || true \
    && rm -f /etc/apache2/mods-enabled/mpm_event.* \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.* \
    && rm -f /etc/apache2/mods-enabled/mpm_prefork.* \
    && a2enmod mpm_prefork rewrite headers

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 10000

CMD rm -f /etc/apache2/mods-enabled/mpm_event.* \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.* \
    && a2enmod mpm_prefork \
    && echo "Listen ${PORT:-10000}" > /etc/apache2/ports.conf \
    && sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT:-10000}>/" /etc/apache2/sites-available/000-default.conf \
    && apache2-foreground