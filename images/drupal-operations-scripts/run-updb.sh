#!/bin/sh

# Change working directory to the drupal code
cd /app

# Run update db 
echo "Running drush updatedb"
/operations/enable-maintenance-mode.sh && drush updatedb -y -q
/operations/disable-maintenance-mode.sh
