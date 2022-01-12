#!/bin/sh

# Create log file under emptydir
touch /var/run/drupal.log

# Tail log file
tail -f /var/run/drupal.log
