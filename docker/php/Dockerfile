FROM php:8.4-fpm

# Set environment variables for Composer and PsySH
ENV COMPOSER_HOME=/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/composer/vendor/bin"
ENV XDG_CONFIG_HOME=/tmp/config
ENV PSYSH_DB_PATH=/tmp/psysh

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd && docker-php-ext-enable gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && mkdir -p /composer/cache /tmp/config/psysh /tmp/psysh \
    && chmod -R 777 /composer /tmp/config /tmp/psysh

# Xdebug configuration
RUN set -eux; \
    { \
      echo "zend_extension=$(php -r 'echo ini_get("extension_dir");')/xdebug.so"; \
      echo "xdebug.mode=debug"; \
      echo "xdebug.start_with_request=yes"; \
      echo "xdebug.client_port=9003"; \
      echo "xdebug.log_level=0"; \
      echo "xdebug.client_host=host.docker.internal"; \
      echo "xdebug.discover_client_host=0"; \
      echo "xdebug.idekey=PHPSTORM"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Set up a working directory for the application
WORKDIR /var/www/html
