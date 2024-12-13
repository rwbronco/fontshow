FROM php:apache

# Installs dependencies required for Composer, mysqli, and PHP extensions.
# 1. Composer required to install php-font-lib.
# 2. Mysqli required for php to db.
# 3. Php-font-lib required to read font metadata.
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
	&& docker-php-ext-install mysqli && docker-php-ext-enable mysqli \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && rm -rf /var/lib/apt/lists/*

# Installs Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Ensure a basic composer.json exists and require PHP FontLib
RUN echo '{}' > composer.json && \
    composer require dompdf/php-font-lib

# Copy custom Apache config file into the container
COPY custom-apache.conf /etc/apache2/conf-available/custom-apache.conf

# Enable custom Apache configuration to get rid of AH00558 Apache error in console, it sets ServerName to localhost
RUN a2enconf custom-apache.conf