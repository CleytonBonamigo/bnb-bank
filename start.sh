#!/bin/sh

set -e
echo "Deploying application ..."

docker compose up -d

# Install dependencies based on lock file
docker exec bnb-bank-php composer install --prefer-dist --optimize-autoloader

docker exec bnb-bank-php cp -n .env.example .env
docker exec bnb-bank-php php artisan key:generate

# Migrate database
docker exec bnb-bank-php php artisan migrate --database=bnb-bank
docker exec bnb-bank-php php artisan migrate --database=bnb-bank-test
# Seeds the database
docker exec bnb-bank-php php artisan db:seed --database=bnb-bank

# Install NPM Dependencies
docker exec bnb-bank-php npm install
# Compile Frontend
docker exec bnb-bank-php npm run build

# Run tests to make sure it's ok to deploy
docker exec bnb-bank-php ./vendor/bin/pest

echo "Application deployed!"