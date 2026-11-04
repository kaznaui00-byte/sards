
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm run build


FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts


FROM php:8.4-apache

RUN apt-get update && apt-get install -y libpq-dev libzip-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install pdo_pgsql mbstring bcmath exif gd zip intl opcache && a2enmod rewrite && sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && rm -rf /var/lib/apt/lists/*

ENV PORT=80

RUN printf 'upload_max_filesize=5M\npost_max_size=6M\n' > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

COPY --from=vendor /app ./
COPY --from=frontend /app/public ./public

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\nAllowOverride All\nRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php artisan package:discover --ansi || true; php artisan storage:link --force || true; if [ \"$RUN_MIGRATIONS\" = \"true\" ]; then php artisan migrate --force || true; fi; if [ -n \"$ADMIN_EMAIL\" ]; then php artisan sard:make-admin \"$ADMIN_EMAIL\" || true; fi; apache2-foreground"]
