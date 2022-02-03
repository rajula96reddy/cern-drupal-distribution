#!/bin/sh
# The script triggers a CronTask inside of Drupal, following these steps:
#   1. Retrieve the cron_key of the website
#   2. Make a http request to the website using the key in order to trigger a CronTask
# Issue link: https://gitlab.cern.ch/webservices/webframeworks-planning/-/issues/437
# Note: If in the future we want to have more periodic actions made to the Drupalsites
# or want to trigger CronTasks in a different way, all we need is to edit this script.
# The DrupalSite operator is only involved to create a CronJob that executes this script.

usage() {
  echo "Usage: $0 --site <site>" 1>&2;
  exit 1;
}

# Options
ARGS=$(getopt -o 's:' --long 'site:' -- "$@") || exit 1
eval "set -- $ARGS"

while true; do
  case "$1" in
    (-s|--site)
      export site="$2"; shift 2;;
    (--) shift; break;;
    (*) usage;;
  esac
done
[[ -z "$site" ]] && usage

echo "Warning: This script has been deprecated and replaced by `/operations/cronjob.sh`"
cron_key=$(drush  state-get system.cron_key --format=string)
if echo "$cron_key" | grep -Eq '^[[:alnum:]]_-]+$' ; then # key can contain alphabetic, numeric, and two special characters.
    echo "$(date) : [${site}] Error cron key not valid. Website might be broken."
    continue
fi

# We have max time / keep alive due to some crons to take longer than expected (it has been reported in Oct/2021 of a website[test-cnpdfs] to take ~5/6min)
# Note: ${site} is sufficient as this curl request will query Kubernetes Service and not any DNS, therefore we do not need to provide siteUrl
HTTP_CODE=$(curl --location --silent -L --max-time 900  --keepalive-time 900 -w "%{http_code}\n" "http://${site}/cron/${cron_key}" -o /dev/null)
case $HTTP_CODE in
    204)
        echo "$(date) : [${site}] ${HTTP_CODE/204/OK}" # Logs not added since pod logs should suffice ? >> "$LOGFILE_MASTER"  204 No Content -> success
    ;;
    504)
        echo "$(date) : [${site}] ${HTTP_CODE/504/NOT OK, ignoring since server might be going through heavy load...}"
    ;;
    *)
        echo "$(date) : [${site}] Error code, expected 204, got ${HTTP_CODE}"
        exit 1
    ;;
esac
