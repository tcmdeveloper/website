#!/bin/bash

echo "🚀 Deploying Laravel..."

# 1. Ensure PHP runtime is correct
php -v

# 2. Hard clear caches
php artisan optimize:clear
rm -rf bootstrap/cache/*.php

# 3. Pull latest code
git pull origin main

# 4. Install dependencies
composer install --no-dev --optimize-autoloader

# 5. Sync frontend assets safely
rm -rf public_html/build
cp -r public/build public_html/

# 6. Sync entry files
cp public/index.php public_html/
cp public/.htaccess public_html/

# 7. Force OPcache reset (IMPORTANT FIX)
php -r "if (function_exists('opcache_reset')) { opcache_reset(); }"

echo "✅ Deployment complete"