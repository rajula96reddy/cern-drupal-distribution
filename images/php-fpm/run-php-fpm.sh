#!/bin/sh
set -ex

INSTALL_COOKIE=/drupal-data/.site-installed
if [ -f "${INSTALL_COOKIE}" ]; then
    # Run enable or disable redis script
    /operations/enable-or-disable-redis.sh
fi

# Run PHP-FPM

exec php-fpm
