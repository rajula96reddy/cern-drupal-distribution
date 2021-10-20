#!/bin/sh

# Enable redis if it's a critical site
if [[ -z "${ENABLE_REDIS}" ]]; then
    echo "ENABLE_REDIS module not set. Therefore uninstalling redis module."
    drush cr
    drush pm:uninstall -y redis
    drush cr
    drush pm:list | grep redis | grep Disabled
    if [ $? -ne "0" ]; then
        exit 1
    fi
else
    echo "ENABLE_REDIS module is set. Therefore enabling redis module."
    drush cr
    drush pm:enable -y redis
    drush cr
    drush pm:list | grep redis | grep Enabled
    if [ $? -ne "0" ]; then
        exit 1
    fi
fi
