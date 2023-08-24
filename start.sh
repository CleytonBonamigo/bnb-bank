#!/bin/sh

set -e
echo "Deploying application ..."

docker compose up -d

# Install dependencies based on lock file
echo "Running composer installment of dependencies"
docker exec bnb-bank-php composer install --prefer-dist --optimize-autoloader

# Install NPM Dependencies
echo "Installing NPM dependencies"
docker exec bnb-bank-php npm install
# Compile Frontend
echo "Compiling frontend stuff"
docker exec bnb-bank-php npm run build

echo "Sleeping for 30 seconds to MySQL initiate completely"
sleep 30

# Migrate database
echo "Migrating Databases"
docker exec bnb-bank-php php artisan migrate --database=bnb-bank
docker exec bnb-bank-php php artisan migrate --database=bnb-bank-test
# Seeds the database
echo "Seeding database"
docker exec bnb-bank-php php artisan db:seed --database=bnb-bank

# Run tests to make sure it's ok to deploy
echo "Running PEST tests"
docker exec bnb-bank-php ./vendor/bin/pest

echo "Application deployed!"