FROM composer as composer
WORKDIR /app/legacy
COPY legacy/composer.json legacy/composer.lock ./
RUN composer install --ignore-platform-reqs --no-dev

FROM php:8.5-apache
RUN apt-get update && apt-get install -y zlib1g-dev \
    libzip-dev \
    libldap2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd ldap zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod deflate
RUN a2enmod filter
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
WORKDIR /var/www/html
COPY --from=composer /app/legacy/vendor ./legacy/vendor
COPY . .
RUN chown www-data legacy/application/logs
