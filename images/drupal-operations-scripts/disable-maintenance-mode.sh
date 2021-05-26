#!/bin/sh

# Change working directory to the drupal code
cd /app

# Disable maintenance mode
echo "Disabling maintenance mode"
drush state:set system.maintenance_mode 0 --input-format=integer
drush cache:rebuild -q
