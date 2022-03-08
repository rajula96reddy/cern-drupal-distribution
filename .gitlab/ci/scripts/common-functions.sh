#!/bin/bash

# Get TAG
get_tag(){
   case "$CI_COMMIT_BRANCH" in
      v*)
        # Ugly implementation so far
           export VERSION_SPEC=$(echo $TAG | tail -c -29)
           export VERSION_NAME=$(echo $TAG | head -c -30)
      ;;
      master)
        # Ugly implementation so far
           export VERSION_SPEC=$(echo $TAG | tail -c -29)
           export VERSION_NAME=$(echo $TAG | head -c -30)
      ;;
      *)
        # Not a great implementation but follows format "$BRANCH_NAME-$COMMIT_HASH"
           export VERSION_SPEC=$(echo $TAG | grep -o '[^-]*$') && export AUX_VERSION_NAME=$(echo $VERSION_SPEC | wc -c)
           export VERSION_NAME=$(echo $TAG | head -c -$AUX_VERSION_NAME | head -c -1)
      ;;
    esac
}

# Delete site Instance
delete_site(){
    oc delete drupalsite/$SITENAME -n $NAMESPACE
}

