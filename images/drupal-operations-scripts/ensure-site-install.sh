#!/bin/sh
set -exu

# We have a cookie to let the job know if it should run 'drush site-install ...'
# Details can be seen here: https://gitlab.cern.ch/webservices/webframeworks-planning/-/issues/484
INSTALL_COOKIE=/drupal-data/.site-installed
if [ -f "${INSTALL_COOKIE}" ]; then
    echo "${INSTALL_COOKIE} exists."
    echo "skipping installation..."
    exit 0
fi

# Change working directory to the drupal code
cd /app

# Drop tables
drush sql:drop -y

# Install Drupal site
echo "Installing Drupal site"
drush site-install cern -y --config-dir=../config/sync --account-name=admin install_configure_form.enable_update_status_emails=NULL -vvv
# We double check that cache does not interfere with user deletion, details can be seen here: https://gitlab.cern.ch/drupal/paas/cern-drupal-distribution/-/merge_requests/33
drush cr
if [ "$?" -ne "0" ]; then
    drush cr
fi
# Remove admin account
drush user-cancel admin -y
drush cr
if [ "$?" -ne "0" ]; then
    drush cr
fi

# Leave cookie
touch $INSTALL_COOKIE
