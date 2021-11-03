#!/bin/sh

redisEnabledCheck="drush pm:list | grep redis | grep -q Enabled"
redisDisabledCheck="drush pm:list | grep redis | grep -q Disabled"

# Enable redis if it's a critical site
if [[ "${ENABLE_REDIS}" != "true" ]]; then
  $(eval $redisDisabledCheck) || {
    echo "ENABLE_REDIS is false, but Redis module enabled."
    echo "Therefore uninstalling redis module."
    drush pm:uninstall -y redis
    drush cr
  }
else
  $(eval $redisEnabledCheck) || {
    echo "ENABLE_REDIS is true, but Redis module disabled."
    echo "Therefore enabling redis module."
    drush pm:enable -y redis
    drush cr
  }
fi
