#!/bin/sh
set -e

php artisan storage:link || true
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
