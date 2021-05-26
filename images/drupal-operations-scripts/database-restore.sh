#!/bin/sh

usage() {
  echo "Usage: $0 --filename <filename.sql>" 1>&2;
  exit 1;
}

# Options
ARGS=$(getopt -o 'f:' --long 'filename:' -- "$@") || exit 1
eval "set -- $ARGS"

while true; do
  case "$1" in
    (-f|--filename)
      export FILENAME="$2"; shift 2;;
    (--) shift; break;;
    (*) usage;;
  esac
done
[[ -z "$FILENAME" ]] && usage

# Change working directory to the drupal code
cd /app

# Database drop
echo "Dropping database"
drush sql-drop -y

# Database restore
echo "Restoring database from" $FILENAME
`drush sql-connect` < /drupal-data/$FILENAME
