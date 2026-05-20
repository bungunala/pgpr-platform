FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-configure pdo_pgsql \
    && docker-php-ext-install pdo_pgsql mbstring exif gd zip json

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN adduser -D -u 1000 -g 'www-data' -s /bin/sh www-data
RUN mkdir -p /var/www/html/writable /var/www/html/writable/uploads \
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