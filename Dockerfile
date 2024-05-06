FROM php:8.1 as php

RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    libxml2-dev  \
    libonig-dev \
    libzip-dev \
    libsocket6-perl 

RUN docker-php-ext-install pdo pdo_mysql bcmath sockets


WORKDIR /var/www
COPY . .

COPY --from=composer:2.7.4 /usr/bin/composer /usr/bin/composer

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
