FROM php:apache

# Install dependencies required for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Ensure a basic composer.json exists and require PHP FontLib
RUN echo '{}' > composer.json && \
    composer require dompdf/php-font-lib

# Copy custom Apache config file into the container
COPY custom-apache.conf /etc/apache2/conf-available/custom-apache.conf

# Enable the custom configuration
RUN a2enconf custom-apache.conf