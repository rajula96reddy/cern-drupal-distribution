#!/bin/sh

usage() {
  echo "Usage: $0 [--filename <filename.sql> || --path <path_for_filename>]" 1>&2;
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
if [ -z "$FILENAME" ]  && [ -z "$FILEPATH" ]; then
    usage
fi

# If both are set, we should also not process
if [ ! -z "$FILENAME" ]  && [ ! -z "$FILEPATH" ]; then
    usage
fi

# Change working directory to the drupal code
cd /app

# Clone data from source to destination
rsync -rv /drupal-data-source/ /drupal-data
if [[ ! -z "$FILEPATH" ]]; then
    /operations/database-restore.sh -p $FILEPATH
else
    /operations/database-restore.sh -f $FILENAME
fi
/operations/clear-cache.sh

# Run enable or disable redis script
/operations/enable-or-disable-redis.sh
