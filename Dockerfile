FROM composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json ./
COPY --from=build /app/vendor ./vendor
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm install && npm run build

FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache nginx postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install opcache

WORKDIR /var/www/html

COPY --from=build /app .
COPY --from=assets /app/public/build ./public/build

COPY nginx.conf /etc/nginx/http.d/default.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]