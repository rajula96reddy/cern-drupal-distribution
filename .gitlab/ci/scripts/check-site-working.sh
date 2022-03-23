#!/bin/bash

usage() { echo "Usage: $0 [--fqdn <FQDN> --sitename <SITENAME> --namespace <NAMESPACE>]" 1>&2; exit 1; }

ARGS=$(getopt -o 'f:s:n:' --long 'fqdn:sitename:namespace:' -- "$@") || exit 1
eval "set -- $ARGS"

while true; do
  case "$1" in
    (-f|--fqdn)
      export FQDN="$2"; shift 2;;
    (-s|--sitename)
      export SITENAME="$2"; shift 2;;
    (-n|--namespace)
      export NAMESPACE="$2"; shift 2;;
    (--) shift; break;;
    (*) usage;;
  esac
done

[[ -z "$FQDN" ]] && usage

# Wait  for clone pod to spawn
sleep 60s
max_attempts="20"
attempt_num="0"
while [[ $(oc get drupalsite/$SITENAME -n $NAMESPACE -o json | jq -r '.status.conditions[] | select(.type=="Ready") | .status') != "True" ]]
do
  if (( $attempt_num == $max_attempts ))
    then
        echo $(date -u +"%Y-%m-%dT%H:%M:%SZ")"-Sitename: ${SITENAME}, Timed out (20minutes) waiting for DrupalSite to be ready!"
        exit 0
    else
        (( attempt_num++ ))
        sleep 60s
    fi
done

# Warm up the website
curl --max-time 60 --silent --fail --insecure -IL "https://${FQDN}/" > /dev/null
curl --max-time 60 --silent --fail --insecure -IL "https://${FQDN}/user/login" > /dev/null
# Give another 10 minutes for SSO etc. to be properly running
sleep 600

# Check if the base URL works
HTTP_CODE_BASE=$(curl --max-time 60 --silent --fail --insecure -I "https://${FQDN}/" -w '%{http_code}\n' -o /dev/null)
[[ "$HTTP_CODE_BASE" -ge "300" ]] && {
    echo "Failed to get a good response from website, $HTTP_CODE_BASE"
    exit 0
}

# Check if the OIDC login redirection works
EXPECTED_URL="https://auth.cern.ch/auth/realms/cern/protocol/openid-connect/"
# We do not check URL_effective but validate that Drupal is redirecting into the expected URL (even if that URL ends up returning 5xx or other error codes, we do not check the code but the url)
CURRENT_URL=$(curl --max-time 60 --silent --fail --insecure -IL "https://${FQDN}/user/login" -w '%{url_effective}\n' -o /dev/null)
if [[ ${CURRENT_URL} != ${EXPECTED_URL}* ]]; then
    echo "SSO failed to return expected URL, URL expected ${EXPECTED_URL}, URL received ${CURRENT_URL}"
    exit 0
fi
echo "True"
