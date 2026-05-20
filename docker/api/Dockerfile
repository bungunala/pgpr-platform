FROM php:8.2-fpm-alpine

ENV PHP_INI_SCAN_DIR=/opt/php/conf.d:/usr/local/etc/php/conf.d

COPY --from=dockerphpext/pdo_pgsql:php8.2-alpine /opt/php/ /opt/php/

RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    unzip

RUN docker-php-ext-install mbstring exif gd zip json

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN adduser -D -u 1000 -g 'www-data' -s /bin/sh www-data \
    && mkdir -p /var/www/html/writable /var/www/html/writable/uploads \
    && chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader || true

RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/custom.ini

EXPOSE 8080

USER www-data

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]