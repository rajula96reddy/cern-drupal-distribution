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
# Need to add --extra-dump=--no-tablespaces to drush sql-dump to be compatible since MySQL 5.7
# https://dev.mysql.com/doc/relnotes/mysql/5.7/en/news-5-7-31.html#mysqld-5-7-31-security
if [[ ! -z "$FILEPATH" ]]; then
    echo "Backing up database to $FILEPATH"
    ## --max_allowed_packets to avoid https://stackoverflow.com/questions/8815445/mysqldump-error-got-packet-bigger-than-max-allowed-packet
    drush sql-dump --extra-dump=--no-tablespaces --extra-dump=--max_allowed_packet=512M > $FILEPATH
else
    echo "Backing up database to /drupal-data/$FILENAME"
    drush sql-dump --extra-dump=--no-tablespaces --extra-dump=--max_allowed_packet=512M > /drupal-data/$FILENAME
fi
