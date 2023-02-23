FROM php:8.1.16-fpm-bullseye

LABEL maintainer="Diego Fernando Yama Andrade"

WORKDIR /var/www
# COPY docker/php.ini /usr/local/etc/php/conf.d/99-sail.ini
ENV TZ=America/Bogota
ENV NODE_ENV=production
ENV PHP_MEMORY_LIMIT=2G
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN apk update && apk add git zip unzip ffmpeg
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd pdo pdo_pgsql pgsql mbstring readline redis xml imap pcntl sodium soap zip && \
    docker-php-ext-enable gd imap pdo_pgsql pgsql redis sodium soap
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
COPY . .
RUN composer install --no-scripts --no-dev -o
# We want to cache the event, routes, and views so we don't try to write them when we are in Kubernetes.
# Docker builds should be as immutable as possible, and this removes a lot of the writing of the live application.
RUN php artisan view:cache && \
    php artisan event:cache && \
    php artisan route:cache && 
    
# Default php fmp port
EXPOSE 9000

# # Arguments defined in docker-compose.yml
# ARG user
# ARG uid

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip \
#     libpq-dev

# # Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# # Install PHP extensions
# RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# # Get latest Composer
# #COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
# COPY . .
# RUN composer install

# # Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user

# # Crate permissions for laravel
# RUN mkdir -p /var/www/storage/framework/{sessions,views,cache} \
#     && mkdir -p /var/www/bootstrap/cache \
#     && mkdir -p /var/www/storage/logs \
#     && addgroup -g ${PHP_UID} www \
#     && adduser -H -D -u ${PHP_GID} -G www www \
#     && chown -R www:www /var/www

# #RUN composer install --no-scripts --no-dev -o

# # Set working directory
# WORKDIR /var/www

# USER $user
