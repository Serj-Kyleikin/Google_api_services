#!/bin/bash

composer create-project --prefer-dist laravel/laravel
sudo chmod -R 777 laravel

sudo cp project/.env.example laravel/.env
sudo cp -rpt laravel project/.

docker-compose up -d --build
docker exec -it application bash -c "composer update && php artisan migrate"

echo "Done! Good luck.."
