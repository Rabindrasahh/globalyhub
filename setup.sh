#!/bin/bash

set -e

echo "🚀 Starting Setup..."

if [ ! -f ".env" ]; then
  echo "📄 Creating .env..."
  cp .env.example .env
fi

echo "🐳 Starting Docker containers..."
docker compose up -d --build

echo "🔧 Fixing git safe directory..."
docker compose exec -u www-globalyhub globalyhub_app git config --global --add safe.directory /var/www || true

echo "🔐 Fixing permissions..."
docker compose exec globalyhub_app bash -c "chown -R www-globalyhub:www-globalyhub /var/www"
docker compose exec globalyhub_app bash -c "chmod -R 775 /var/www"

echo "📦 Installing dependencies..."
docker compose exec -u www-globalyhub globalyhub_app composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🔑 Generating app key..."
docker compose exec -u www-globalyhub globalyhub_app php artisan key:generate

echo "🗄️ Running migrations..."
docker compose exec -u www-globalyhub globalyhub_app php artisan migrate --force

echo "✅ Project Setup Done!"