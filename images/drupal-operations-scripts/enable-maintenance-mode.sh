#!/bin/sh

# Change working directory to the drupal code
cd /app

# Enable maintenance mode
echo "Enabling maintenance mode"
drush state:set system.maintenance_mode 1 --input-format=integer
