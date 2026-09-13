#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not set; generating a temporary key..." >&2
    export APP_KEY="base64:$(php artisan key:generate --show --no-interaction)"
fi

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running migrations..." >&2
    php artisan migrate --force || echo "Migration failed (continuing)" >&2
fi

php artisan config:cache --no-interaction || true
php artisan route:cache --no-interaction || true
php artisan view:cache --no-interaction || true
php artisan storage:link --no-interaction || true

mkdir -p /run/nginx

echo "Starting nginx and php-fpm..." >&2
nginx -g 'daemon off;' &
exec php-fpm