#!/bin/bash
set -e

# Render assigns a random port via $PORT - Apache must listen on that, not 80.
PORT="${PORT:-8080}"
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

cd /var/www/html

# Make sure Laravel's writable directories exist every time the container starts -
# a previous deploy crashed here because storage/framework/views didn't exist yet.
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Safe to run on every boot - migrate only applies new migrations, never re-runs old ones.
php artisan migrate --force

apache2-foreground