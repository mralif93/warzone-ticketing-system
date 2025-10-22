#!/bin/bash

# Docker entrypoint script for Laravel application

set -e

echo "Starting Warzone Ticketing System..."

# Wait for database to be ready
echo "Waiting for PostgreSQL to be ready..."
while ! nc -z postgres 5432; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 2
done
echo "PostgreSQL is up - continuing"

# Wait for Redis to be ready
echo "Waiting for Redis to be ready..."
while ! nc -z redis 6379; do
  echo "Redis is unavailable - sleeping"
  sleep 2
done
echo "Redis is up - continuing"

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Install Composer dependencies
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Install NPM dependencies and build assets
if [ ! -d "/var/www/html/node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

echo "Building frontend assets..."
npm run build

# Generate application key if not exists
if [ ! -f "/var/www/html/.env" ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

if [ -z "$(grep APP_KEY .env 2>/dev/null)" ] || [ "$(grep APP_KEY .env 2>/dev/null)" = "APP_KEY=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed database if environment variable is set
if [ "$SEED_DATABASE" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

# Clear and cache configuration
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Warzone Ticketing System is ready!"

# Execute the main command
exec "$@"
