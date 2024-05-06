#!/bin/bash

if [ -f "vendor/autoload.php" ]; then
    composer install --no-interaction --no-progress
fi

: << COMMENT
if [ -f ".env" ];then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "Env file already exists"
fi

role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    php artisan migrate
    php artisan key:generate
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear

    php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
    exec docker-php-entrypoint "$@"
COMMENT

if [ "$role" = "queue" ]; then
    echo "Running the queue"
    php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90
elif [ "$role" = "websocket" ]; then
    echo "Running the websocket server"
    php artisan websockets:serve
fi

