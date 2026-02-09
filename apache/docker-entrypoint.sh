#!/bin/sh

uid="$(id -u)"
gid="$(id -g)"
if [ "$uid" = '0' ]; then
    case "$1" in
        apache2*)
            user="${APACHE_RUN_USER:-www-data}"
            group="${APACHE_RUN_GROUP:-www-data}"

        user="${user#'#'}"
        group="${group#'#'}"
        ;;
        *) # php-fpm
            user='www-data'
            group='www-data'
            ;;
    esac
else
    user="$uid"
    group="$gid"
fi

chown -R "$user":"$group" .

CONFIG_FILE="/var/www/html/config/local/config.local.ini"

if [ ! -f "$CONFIG_FILE" ]; then
  php /var/www/html/install/docker-base-install.php
fi

exec "$@"
