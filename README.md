# BGG App

Application Web voué à afficher des statistiques provenant du site web boardgamegeek.

Version 1.1

# Environnement local

docker-compose up -d
docker-compose exec app composer update

http://localhost:8181

## Autre commandes

docker-compose exec app php artisan

## Compilation des assets

docker-compose run --rm npm install

docker-compose run --rm npm run dev

## Installation serveur

docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed