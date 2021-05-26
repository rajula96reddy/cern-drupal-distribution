#!/bin/sh

# Change working directory to the drupal code
cd /app

# Check if Drupal site is installed
drush status bootstrap | grep -q Successful

# Make sure we get a successful response
  if [ "$?" -ne "0" ]; then
    echo "Drupal is not installed" >&2
    exit 1
  fi
  exit 0
