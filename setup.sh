#!/bin/bash
set -e

echo "🚀 Starting Laravel Docker Setup..."

# Create .env if it does not exist
[ ! -f .env ] && cp .env.example .env

# Build and start containers
docker compose up -d --build

# Set git safe directory inside container
docker compose exec -u root globalyhub_app git config --global --add safe.directory /var/www || true

# Fix ownership and permissions as root
docker compose exec -u root globalyhub_app bash -c "
  chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache &&
  chmod -R 775 /var/www/storage /var/www/bootstrap/cache
"

# Install PHP dependencies
docker compose exec -u root globalyhub_app composer install
# Generate app key if not set
grep -q '^APP_KEY=.\+' .env || docker compose exec -u root globalyhub_app php artisan key:generate

# Run migrations & seeders
docker compose exec -u root globalyhub_app php artisan migrate --force --seed

echo "✅ Laravel Project Setup Done!"