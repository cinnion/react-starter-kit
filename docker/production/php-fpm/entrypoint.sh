#!/bin/sh
set -e

# Initialize storage directory if empty
# -----------------------------------------------------------
# If the storage directory is empty, copy the initial contents
# -----------------------------------------------------------
if [ ! "$(ls -A /var/www/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
fi

# Remove storage-init directory
rm -rf /var/www/storage-init

# Initialize /var/www/public with the latest built contents.
echo "Initializing /var/www/public..."
rm -rf /var/www/public/*
cp -R /var/www/public-init/. /var/www/public

# Remove the public-init directory
rm -rf /var/www/public-init

# Run Laravel migrations
# -----------------------------------------------------------
# Ensure the database schema is up to date.
# -----------------------------------------------------------
php artisan migrate --force

# Clear and cache configurations
# -----------------------------------------------------------
# Improves performance by caching config, routes and views.
# -----------------------------------------------------------
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run the default command
exec "$@"
