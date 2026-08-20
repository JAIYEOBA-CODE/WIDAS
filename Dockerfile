FROM node:22-bookworm AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY tsconfig.json vite.config.js vite.config.ts ./
RUN npm run build

FROM php:8.3-cli-bookworm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        libonig-dev \
        libpq-dev \
        libsqlite3-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-install bcmath dom mbstring pdo_mysql pdo_pgsql pdo_sqlite zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && chmod +x docker/start.sh \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY --from=assets /app/public/build ./public/build

EXPOSE 10000

CMD ["docker/start.sh"]
