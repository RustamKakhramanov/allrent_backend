#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#

FROM php:8.3-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND=noninteractive

#
#--------------------------------------------------------------------------
# Software's Installation
#--------------------------------------------------------------------------
#
# Installing tools and PHP extensions using "apt", "docker-php", "pecl",
#

# Install required packages
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev \
            libexif-dev \
            git \
            unzip; \
    rm -rf /var/lib/apt/lists/*

# Install the PHP extensions
RUN set -eux; \
    # Install the PHP pdo_mysql extension
    docker-php-ext-install pdo_mysql; \
    # Install the PHP pdo_pgsql extension
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    # Install the PHP exif extension
    docker-php-ext-install exif; \
    php -r 'var_dump(gd_info());'

# Install additional PHP extensions via PECL if needed
RUN set -eux; \
    pecl install redis; \
    docker-php-ext-enable redis

# Copy application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
