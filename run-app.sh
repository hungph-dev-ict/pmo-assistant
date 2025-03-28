#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-app.sh`
sh -c "service nginx start && php-fpm && php artisan migrate --force && npm run dev "