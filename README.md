# BGG App

Application Web voué à afficher des statistiques provenant du site web boardgamegeek.

Version 1.1

# Environnement local

docker-compose up -d
docker-compose exec app composer update

http://localhost:8000

## Autre commandes

docker-compose exec app php artisan

## Compilation des assets

docker-compose run --rm npm install

docker-compose run --rm npm run dev
