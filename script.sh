#!/bin/bash

php_my_admin=phpmyadmin
nginx=webserver
mysql=db
app=app
scrap=scrap

echo "Launching Hackaton Banque de France"

docker-compose up -d $php_my_admin $nginx $mysql $app

echo "Waiting for all the containers to be up and running"

until [ "`docker inspect -f {{.State.Running}} $php_my_admin`"=="true" ] \
    && [ "`docker inspect -f {{.State.Running}} $nginx`"=="true" ] \
    && [ "`docker inspect -f {{.State.Running}} $mysql`"=="true" ] \
    && [ "`docker inspect -f {{.State.Running}} $app`"=="true" ]; do
    sleep 0.1;
done;

echo "populate database"

docker-compose exec $app composer install

docker-compose exec $app php artisan migrate

echo "Run scrapper"

docker-compose up $scrap

echo "Done"
