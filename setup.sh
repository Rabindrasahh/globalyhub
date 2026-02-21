#!/bin/bash

set -e

echo " Starting Setup..."

if [ ! -f ".env" ]; then
  echo "Creating .env..."
  cp .env.example .env
fi

docker-compose up -d --build

docker compose exec globalyhub_app composer install
docker compose exec globalyhub_app php artisan key:generate
docker compose exec globalyhub_app php artisan migrate

echo "Project Setup Done!"