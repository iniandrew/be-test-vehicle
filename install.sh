#!/bin/bash

docker compose up -d --build \
    && docker exec -it app composer install \
    && docker exec -it app php artisan key:generate \
    && docker exec -it app php artisan jwt:secret \
    && docker exec -it app php artisan db:seed \
    && docker exec -it app php artisan storage:link \
    && docker exec -it app php artisan optimize
