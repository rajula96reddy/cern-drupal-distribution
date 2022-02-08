#!/bin/sh

LOGS_DIR="/drupal-data/updb-logs/"
DATE=$(date -u +%Y.%m.%dT%H-%M-%SZ)

# Change working directory to the drupal code
cd /app
[[ -d "$LOGS_DIR" ]] || mkdir "$LOGS_DIR"

# Run update db
echo "Running drush updatedb"
/operations/enable-maintenance-mode.sh
drush updatedb -y 1>&2 2> "$LOGS_DIR/$DATE"
/operations/disable-maintenance-mode.sh
