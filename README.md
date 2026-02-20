# 🚀 Notification System – Laravel 12 (Dockerized)

# 📦 Tech Stack

- Laravel 12
- PHP 8.4 (FPM)
- PostgreSQL 16 Database
- Redis 7
- Nginx Web Server
- Docker & Docker Compose

---

# 📁 Features

## ✅ Part 1 – Notification Publishing API

- Create notifications via REST API
- Store notifications in PostgreSQL
- Publish notification jobs to Redis Queue

## ✅ Part 2 – Queue Processing

- Redis-based queue worker
- Simulated notification sending using `Log::info()`
- Automatic retry with exponential backoff
- Status updates in database (`pending`, `processed`, `failed`)

## ✅ Part 3 – Monitoring APIs

- Retrieve recent notifications (with filters)
- Get notification summary (counts by status)

---

# 🐳 Docker Setup Guide

## 1️⃣ Clone the Repository
# globalyhub
