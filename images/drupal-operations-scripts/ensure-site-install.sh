#!/bin/sh

usage() {
  echo "Usage: $0 --profile <cern|easystart>" 1>&2;
  exit 1;
}

# Options
ARGS=$(getopt -o 'p:' --long 'profile:' -- "$@") || exit 1
eval "set -- $ARGS"

while true; do
  case "$1" in
    (-p|--profile)
      export PROFILE="$2"; shift 2;;
    (--) shift; break;;
    (*) usage;;
  esac
done
[[ -z "$PROFILE" ]] && usage

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
drush site-install $PROFILE -y --config-dir=../config/sync --account-name=admin install_configure_form.enable_update_status_emails=NULL -vvv
# Remove admin account
drush user-cancel admin -y
drush cr
if [ "$?" -ne "0" ]; then
    drush cr
fi

# Leave cookie
touch $INSTALL_COOKIE
