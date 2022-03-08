#!/bin/bash

source `dirname $0`/common-functions.sh

# Ensure oc access
oc login -u ${SA_USER} -p ${SA_PASSWORD} --server=https://api.drupal.okd.cern.ch

# $NAMESPACE is defined on .gitlab-ci.yaml
# Create/ValidateProject
oc new-project $NAMESPACE --description="Project created by CI on Cern-drupal-distribution"

# Variables to create new DrupalSite
get_tag
# We have no quotation marks because we want double interpolation of the site variable
export SITENAME=${SITENAME}
export RELEASE_NAME="${IMAGE_NAME}"
export RELEASE_SPEC="${TAG}"
export DISK_SIZE="1Gi"
export FQDN="${SITENAME}.webtest.cern.ch"

# Create DrupalSite
envsubst < `dirname $0`/../resources/newDrupalsite.yaml | oc apply -f -
echo "🚀 Site will be deployed on ${FQDN}"

# Test instance is working
## curl the site's login path and see if it responds
working=$(./`dirname $0`/../scripts/check-site-working.sh -f "${FQDN}" -s ${SITENAME} -n ${NAMESPACE})
if [[ $working != "True" ]]; then
    echo "❌ Website failed to be provisioned as expected, error: ${working}"
    delete_site
    exit 1
fi

# If website contains the previous grepped string, then it means it failed to fully migrate
CHECK_IF_SITE_INSTALL=$(curl --max-time 60 --silent --fail "https://${FQDN}/" | grep "Welcome to Drush Site-Install")
[[ -z "${CHECK_IF_SITE_INSTALL}" ]] && {
    echo "❌ Welcome Drush page not there"
    delete_site
    exit 1
}

echo "✅ Website provisioned as expected!"
delete_site
# Don't delete Project, as it might be used on othe CIs at the same time, just delete resources!
