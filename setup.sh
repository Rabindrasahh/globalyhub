#!/bin/bash  
set -e

# export USER_ID=$(id -u)
# export GROUP_ID=$(id -g)

echo "🚀 Starting Setup..."

# Create .env if not exists
[ ! -f .env ] && cp .env.example .env

# Start containers
docker compose up -d --build

# Git safe directory
docker compose exec globalyhub_app git config --global --add safe.directory /var/www || true

chmod -R 775 storage bootstrap/cache || true

docker compose exec globalyhub_app composer install

docker compose exec globalyhub_app php artisan key:generate
docker compose exec globalyhub_app php artisan migrate --force --seed

echo "Project Setup Done!"