#!/bin/bash

set -e

cd "$(dirname "$0")"

# Run outside the container, but PHP through it (the host misses the dom/xml extensions).

if [ -z "$(docker compose ps -q --status running php-fpm)" ]; then
    echo "The php-fpm container is not running, start it with 'docker compose up -d'." >&2
    exit 1
fi

php_fpm() {
    docker compose exec -T php-fpm "$@"
}

# Dependencies

php_fpm composer install --no-dev --optimize-autoloader

# Empty Storage

find storage/app -type f ! -name '.gitignore' -exec rm -f {} \;
find storage/logs -type f ! -name '.gitignore' -exec rm -f {} \;

# Clear Cache

php_fpm php artisan cache:clear
php_fpm php artisan route:clear
php_fpm php artisan config:clear
php_fpm php artisan view:clear
php_fpm php artisan clear-compiled

# Remove Various

rm -f apphold-0.0.0.zip

rm -f public/hot

find . -name ".DS_Store" -delete

# Zip Files

zip -r apphold-0.0.0.zip . \
    -x '.git/*' \
    -x '.idea/*' \
    -x '.run/*' \
    -x 'docker/*' \
    -x 'node_modules/*' \
    -x 'tests/*' \
    -x '.editorconfig' \
    -x '.gitattributes' \
    -x '.gitignore' \
    -x '.prettierignore' \
    -x '.package-lock.json' \
    -x '.env' \
    -x 'build.sh' \
    -x 'docker-compose.yml' \
    -x 'postcss.config.js' \
    -x 'vite.config.js' \
    -x 'SPECS.md' \
    -x '*.zip'
