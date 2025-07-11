FROM node:20 AS vite-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.4-fpm
RUN apt-get update && apt-get install -y libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www

COPY --from=vite-builder /app/public/build /var/www/public/build
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
