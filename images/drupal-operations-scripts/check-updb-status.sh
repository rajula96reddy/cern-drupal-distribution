#!/bin/sh

# Change working directory to the drupal code
cd /app

# Check updb status
drush updatedb-status --no-entity-updates --format=json 2>/dev/null | jq '. | length'
