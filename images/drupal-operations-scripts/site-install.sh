#!/bin/sh
set -exu

# Change working directory to the drupal code
cd /app

# Drop tables
drush sql:drop -y

# Install Drupal site
echo "Installing Drupal site"
drush site-install cern -y --config-dir=../config/sync --account-name=admin install_configure_form.enable_update_status_emails=NULL
# Remove admin account
drush user-cancel admin -y
drush cr
if [ "$?" -ne "0" ]; then
    drush cr
fi
