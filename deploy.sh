#!/bin/bash

echo "🚀 Deploying Laravel..."

# 1. Pull latest code
git pull origin main

# 2. Install PHP deps (optional on server)
composer install --no-dev --optimize-autoloader

# 3. Sync ONLY safe public files
cp public/index.php public_html/
cp public/phpinfo.php public_html/
cp public/.htaccess public_html/

# 4. Sync Vite build (critical)
rm -rf public_html/build
cp -r public/build public_html/

# 5. Clear Laravel cache
php artisan optimize:clear

echo "✅ Deployment complete"
