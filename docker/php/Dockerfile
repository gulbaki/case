FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libicu-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \ 
    && docker-php-ext-install intl pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
