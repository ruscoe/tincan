#!/bin/bash
set -e

# Setup permissions
mkdir -p uploads
chown -R www-data:www-data uploads
chmod -R 755 uploads

# Start services
/etc/init.d/php8.1-fpm start
nginx -g "daemon off;"
