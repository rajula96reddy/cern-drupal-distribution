#!/bin/sh

usage() {
  echo "Usage: $0 [ --filename <filename.sql> || --path <path_for_file>]" 1>&2;
  exit 1;
}

# Options
ARGS=$(getopt -o 'f:p:' --long 'filename:,path:' -- "$@") || exit 1
eval "set -- $ARGS"

while true; do
  case "$1" in
    (-f|--filename)
      export FILENAME="$2"; shift 2;;
    (-p|--path)
      export FILEPATH="$2"; shift 2;;
    (--) shift; break;;
    (*) usage;;
  esac
done
if [ -z "$FILENAME" ] && [ -z "$FILEPATH" ]; then
    usage
fi
# If both are set, we should not process either
if [ ! -z "$FILENAME" ] && [ ! -z "$FILEPATH" ]; then
    usage
fi
# Change working directory to the drupal code
cd /app

# Database backup
if [[ ! -z "$FILEPATH" ]]; then
    echo "Backing up database to $FILEPATH"
    drush sql-dump > $FILEPATH
else
    echo "Backing up database to /drupal-data/$FILENAME"
    drush sql-dump > /drupal-data/$FILENAME
fi
