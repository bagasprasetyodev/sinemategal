FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    sqlite \
    sqlite-dev \
    sqlite-libs \
    curl \
    git \
    unzip \
    libzip-dev \
    oniguruma-dev

RUN docker-php-ext-install pdo pdo_sqlite mbstring zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --ignore-platform-req=php --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 777 storage bootstrap/cache database

EXPOSE 8000

CMD ["sh", "-c", "touch database/database.sqlite && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
