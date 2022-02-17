#!/bin/sh

# We're doing a GET request to localhost:8080/_site/_php-fpm-status. The request will go first to nginx
# and then to php-fpm. The startup probe is on the php-fpm container.
# Doing the request directly to the unix socket in php-fpm didn't work.
# Related Issue: https://gitlab.cern.ch/webservices/webframeworks-planning/-/issues/616
HTTP_CODE_BASE=$(curl --max-time 200 --silent --fail --insecure -I localhost:8080/_site/_php-fpm-status -w '%{http_code}\n' -o /dev/null)
if [[ "${HTTP_CODE_BASE}" -ne "200" ]]; then
    exit 1
fi

exit 0
