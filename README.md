# 🚀 Notification System – Laravel 12 (Dockerized)

### Tech Stack

- Laravel 12
- PHP 8.4 (FPM)
- PostgreSQL 16 Database
- Redis 7
- Nginx Web Server
- Docker & Docker Compose

## Features

### Part 1 – Notification Publishing API

- Create notifications via REST API
- Store notifications in PostgreSQL
- Publish notification jobs to Redis Queue

### Part 2 – Queue Processing

- Redis-based queue worker
- Automatic retry with exponential backoff
- Status updates in database (`pending`, `processed`, `failed`)

### Part 3 – Monitoring APIs

- Retrieve recent notifications (with filters)
- Get notification summary (counts by status)

### 🐳 Docker Setup Guide

#### Clone the Repository

git clone https://github.com/Rabindrasahh/globalyhub.git
cd globalyhub

#### Copy Environment File

cp .env.example .env

#### Build Docker Containers

docker-compose build

#### Start Containers

docker-compose up -d

#### Install Dependencies (Composer)

docker compose exec globalyhub_app bash
composer install

#### Generate Application Key

php artisan key:generate

#### Run Migrations

php artisan migrate

#### Access the Application

http://localhost:8000

## Alternative Method

### Setup using bash command

./setup.sh
