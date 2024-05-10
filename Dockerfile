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

# Install Composer dependencies
COPY composer.json composer.lock ./
RUN composer install --no-progress --no-interaction

# Install Laravel Websockets if needed
RUN composer require beyondcode/laravel-websockets -w

# Set environment variables if needed
ENV PORT=8000
ENTRYPOINT [ "docker/entrypoint.sh" ]


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

WORKDIR /TestServer/frontend/Laravel/hackathon_frontend
COPY . .

RUN pip install --no-cache-dir -r requirements.txt

CMD [ "python", "heartbeat.py" ]



