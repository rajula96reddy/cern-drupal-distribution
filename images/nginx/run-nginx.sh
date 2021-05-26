#!/bin/sh
set -ex

# Run Nginx
# For debugging change nginx to nginx-debug

exec nginx -g "daemon off;"
