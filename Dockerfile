FROM php:8.0-apache

# Install packages
RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

# Remove memory constraint from php
RUN sed -ri -e 's!memory_limit = 128M!memory_limit = -1!g' /usr/local/etc/php/php.ini-*
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Configuring Postgres
RUN docker-php-ext-configure pgsql \
    -with-pgsql=/usr/local/pgsql

# Common PHP Extensions
RUN docker-php-ext-install \
    gd \
    zip \
    bz2 \
    intl \
    iconv \
    pcntl \
    pgsql \
    bcmath \
    opcache \
    calendar \
    pdo_pgsql \
    pdo_mysql

# Copy code and run composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/tmp
RUN cd /var/www/tmp && composer install --no-dev

# Ensure the entrypoint file can be run
RUN chmod +x /var/www/tmp/entrypoint.sh
ENTRYPOINT ["/var/www/tmp/entrypoint.sh"]
