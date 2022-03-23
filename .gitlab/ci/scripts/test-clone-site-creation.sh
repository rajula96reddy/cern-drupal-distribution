#!/bin/bash

source `dirname $0`/common-functions.sh

# Ensure oc access
oc login -u ${SA_USER} -p ${SA_PASSWORD} --server=https://api.drupal.okd.cern.ch

# NAMESPACE is provided by .gitlab-ci.yml, the namespace/project is expected to exist at this point
oc get project ${NAMESPACE}  > /dev/null
if [[ "$?" != "0" ]]; then
    echo "‼️ Project ${NAMESPACE} doesn't exist! Aborting...."
    exit 1
fi


export CLONE_FROM=$(oc get drupalsite -l ci-clone-source=true --no-headers -o custom-columns=name:.metadata.name)
# Validate we only get one website to clone from
if [[ $(echo $CLONE_FROM | wc -w )  != "1" ]]; then
    echo "Error, expected 1 website to clone from, got $CLONE_FROM"
    exit 1
fi

# Variables to clone DrupalSite
get_tag
# We have no quotation marks because we want double interpolation of the site variable
export SITENAME=${SITENAME}
export RELEASE_NAME="${IMAGE_NAME}"
export RELEASE_SPEC="${TAG}"
export DISK_SIZE="1Gi"

# Clone DrupalSite
envsubst < `dirname $0`/../resources/cloneDrupalsite.yaml | oc apply -f -
echo "🚀 Site will be deployed on ${SITENAME}.webtest.cern.ch"

# Test instance is working
## curl the site's login path and see if it responds
working=$(./`dirname $0`/../scripts/check-site-working.sh -f "${SITENAME}.webtest.cern.ch" -s "${SITENAME}" -n "${NAMESPACE}")
if [[ $working != "True" ]]; then
    echo "❌ Website failed to be provisioned as expected, error: ${working}"
    delete_site
    exit 1
fi

echo "✅ Website provisioned as expected!"
delete_site
