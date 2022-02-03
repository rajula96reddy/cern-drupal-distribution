#!/bin/sh
set -ex

LIVENESS_PROBE_FAILURE_FILE=/var/run/liveness_probe_failure

# Export cron_key value to a file in emptyDir
echo $(drush  state-get system.cron_key --format=string) > /var/run/cronkey &

# Check if php-fpm container has been restarted due to Liveness probe
if [ -f "${LIVENESS_PROBE_FAILURE_FILE}" ]; then
    if [[ $(wc -l <${LIVENESS_PROBE_FAILURE_FILE}) -ge 5 ]]; then
        rm -rf ${LIVENESS_PROBE_FAILURE_FILE}
        echo "Multiple liveness probe failures detected. Attempt to restore by clearing caches."
        drush cr
    fi
fi

# Run PHP-FPM
exec php-fpm
