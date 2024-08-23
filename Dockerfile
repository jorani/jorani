FROM composer as composer
COPY composer.json composer.lock ./
RUN composer install --ignore-platform-reqs --no-dev

FROM php:8.1-apache
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
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=composer /app/vendor /var/www/html/vendor
COPY . .
RUN chown www-data application/logs local/upload/leaves/
COPY docker/config.php docker/database.php docker/email.php application/config/
