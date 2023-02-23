FROM php:8.1.16-fpm

LABEL maintainer="Diego Fernando Yama Andrade"

# Copy composer.lock and composer.json into the working directory
COPY composer.json /var/www/

# Set working directory
WORKDIR /var/www/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libfreetype6-dev \
    locales \
    zip \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalamos dependendencias de composer
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents to the working directory
COPY . /var/www
COPY .env.example /var/www/.env

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Assign permissions of the working directory to the www-data user
RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/storage/ && \
    chmod 777 -R /var/www/bootstrap/ && \
    chmod 777 -R /var/www/storage/logs

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000
CMD ["php-fpm"]