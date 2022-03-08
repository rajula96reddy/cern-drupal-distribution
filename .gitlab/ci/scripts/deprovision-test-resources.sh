#!/bin/bash

source `dirname $0`/common-functions.sh
# Ensure oc access
oc login -u "${SA_USER}" -p "${SA_PASSWORD}" --server=https://api.drupal.okd.cern.ch

# $NAMESPACE and $TAG are defined on .gitlab-ci.yaml
# Currently we only delete drupalsites, if in the future we create more resources we should add them here
oc delete drupalsite -n "${NAMESPACE}" -l "drupal.cern.ch/cdd-ci=${PIPELINE_ID}"
exit $?
