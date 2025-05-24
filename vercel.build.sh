#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies
npm install

# Build assets
npm run build

# Cache Laravel configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link 