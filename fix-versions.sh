# This script locates the current version of every required package in `composer.lock`
# and replaces its requirement on `composer.json` with the current version and `~` operator

# prepare req-pkg-version
jq -r '.require | keys | .[]' composer.json > req-pkg
jq -r '.packages[] | (.name + ": " + .version)' composer.lock  > pkg-version
for pkg in `cat req-pkg`; do
  grep -e "$pkg:" pkg-version
done | sort -u > req-pkg-version
sed -i 's/: /: ~/' req-pkg-version

replace(){
  jq ".require.\"$1\" = \"$2\"" composer.json
}

# iterate over req-pkg-version and replace in composer.json
cat req-pkg-version |
  while read l || [[ -n $l ]]; do
    pkg="`echo $l | cut -d: -f1`"; ver="`echo $l | cut -d: -f2`"
    contents="$(replace $pkg $ver)" && echo "${contents}" > composer.json
  done
