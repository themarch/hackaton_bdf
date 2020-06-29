echo "Launching Hackaton Banque de France"

docker-compose up

docker-compose exec app php artisan migrate

echo "Done"
