#!/bin/sh
set -ex

# Export cron_key value to a file in emptyDir
echo $(drush  state-get system.cron_key --format=string) > /var/run/cronkey &

# Run PHP-FPM
exec php-fpm
