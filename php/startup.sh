#!/bin/sh

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! nc -z postgres 5432; do
  sleep 1
done
echo "Database is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis to be ready..."
while ! nc -z redis 6379; do
  sleep 1
done
echo "Redis is ready!"

# Set proper permissions (excluding .git directory)
find /var/www/html -path /var/www/html/.git -prune -o -type f -exec chown www-data:www-data {} \;
find /var/www/html -path /var/www/html/.git -prune -o -type d -exec chown www-data:www-data {} \;
chmod -R 755 /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Fix git ownership issue
git config --global --add safe.directory /var/www/html

# Install dependencies if vendor directory doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Generate application key if not exists
if [ -z "$(grep APP_KEY .env 2>/dev/null)" ] || [ "$(grep APP_KEY .env 2>/dev/null)" = "APP_KEY=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || {
    echo "Migration failed, trying to reset and migrate again..."
    php artisan migrate:fresh --force
}

# Seed database if needed
if [ "$SEED_DATABASE" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec "$@"
