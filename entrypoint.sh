#!/bin/sh
set -e

PORT="${PORT:-8000}"

echo "==========================================="
echo "  SINEMA TEGAL - INSIALISASI SISTEM"
echo "==========================================="

# Pastikan folder penyimpanan dan cache tersedia
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         storage/logs \
         bootstrap/cache \
         database

chmod -R 777 storage bootstrap/cache database 2>/dev/null || true

# Salin .env.example jika .env belum ada
if [ ! -f .env ]; then
    echo "Membuat file .env dari .env.example..."
    cp .env.example .env
fi

# Pastikan file database SQLite ada
if [ ! -f database/database.sqlite ]; then
    echo "Membuat file database/database.sqlite..."
    touch database/database.sqlite
    chmod 666 database/database.sqlite
fi

# Migrasi dan seeding database
echo "Menjalankan migrasi dan seeder database Sinema Tegal..."
php artisan migrate --force --no-interaction || true
php artisan db:seed --force --no-interaction || true

# Bersihkan config cache agar fallback langsung terbaca
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

echo "Sinema Tegal siap! Menjalankan web server pada port: $PORT"
exec php -t public -d variables_order=EGPCS -S 0.0.0.0:"$PORT" server.php
