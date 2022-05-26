#!/bin/bash

set -eEuo pipefail

cp -R /var/www/tmp/. /var/www/html/
chown -R www-data:www-data /var/www/html

ROLE=${CONTAINER_ROLE:-service}
MODE=${APP_ENV:-local}

sleep 20

if [[ $MODE == "production" ]]; then
    echo Optimizing application.
    (cd /var/www/tmp && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force)
fi

case $ROLE in
"service")
    echo Running apache.
    exec apache2-foreground
    ;;
"worker")
    php /var/www/html/artisan horizon
    ;;
"scheduler")
    while true; do
        php /var/www/html/artisan schedule:run --verbose --no-interaction &
        sleep 60
    done
    ;;
*)
    echo ERROR: Failed to assign workload to the container with role: $ROLE
    exit 1
    ;;
esac
