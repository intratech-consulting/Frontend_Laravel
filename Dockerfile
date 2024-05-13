# Use PHP 8.3 FPM image as the base
FROM php:8.3-fpm as php

# Install Git
RUN apt-get update && apt-get install -y git

# Install additional dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    libxml2-dev  \
    libonig-dev \
    libzip-dev \
    libsocket6-perl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath sockets mbstring

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:2.7.4 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
# Update Composer dependencies and install
RUN composer self-update --2 && \
    composer update --no-interaction --prefer-dist && \
    composer install --no-progress --no-interaction || (cat /var/www/storage/logs/*.log && exit 1)

# Copy .env.example to .env
RUN cp .env.example .env

# Generate application key
RUN php artisan key:generate

# Set environment variables if needed
ENV PORT=8000

CMD ["php-fpm"]

##############################################################################
# Node
FROM node:14-alpine as node

WORKDIR /var/www
COPY . .

RUN npm install

VOLUME /var/www/node_modules

##############################################################################
# Heartbeat
FROM python:3

WORKDIR /TestServer/frontend/Laravel/Frontend_Laravel
COPY . .

RUN pip install --no-cache-dir -r requirements.txt

CMD [ "python", "heartbeat.py" ]



