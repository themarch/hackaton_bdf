#!/bin/bash

docker-compose down -v 
docker rm $(docker ps -a)

 if [ "$#" -eq  "0" ]
   then
       echo "No limit with the scrapper"
    else
         sed -i '' 's/reqs_authors = (grequests.get(link) for link in urls_author)/reqs_authors = (grequests.get(link) for link in urls_author[:'"$1"'])/g' scraper/scraper.py
         echo "Scrapping with limit" $1
fi

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

docker-compose up -d $scrap

until [ "`docker inspect -f {{.State.Running}} $scrap`"=="true" ]; do
    sleep 0.1;
done;

docker exec -ti $(docker ps | grep scraper | cut -d " " -f 1) python ./scraper.py

 if [ "$#" -eq  "0" ]
   then
       echo "Almost done, wait ! without"
    else
         sed -i '' 's/.*reqs_authors = (grequests.get(link) for link in.*)/    reqs_authors = (grequests.get(link) for link in urls_author)/g' scraper/scraper.py
         echo "Almost done, wait !"
fi

echo "Done"
