#!/bin/sh

# Change working directory to the drupal code
cd /app

# Clean cache
drush cr -q
