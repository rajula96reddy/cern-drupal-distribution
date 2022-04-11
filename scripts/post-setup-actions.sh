#!/bin/sh

# Slow down robots to preserve bandwidth
echo "Crawl-Delay: 20" >> web/robots.txt

# Install JS libraries needed by module views_slideshow , ckeditor and maybe others
# Ref: https://www.drupal.org/project/views_slideshow/issues/2673910#comment-13385016

# Move libraries that are installed by composer in the wrong place
mv vendor/bower-asset/jquery-cycle web/libraries/jquery.cycle
mv vendor/bower-asset/jquery-hoverintent web/libraries/jquery.hoverintent
mv vendor/bower-asset/json2 web/libraries/json2
mv vendor/bower-asset/ckeditor web/libraries/ckeditor

# Download JS libs that aren't part of any identifiable package
# The env vars like
#      CKEDITOR_PLUGIN_CODESNIPPET_VERSION
# are set based on the env vars defined in composer.json in the "scripts" section
CKEDITOR_PLUGIN_CODESNIPPET_VERSION="$CKEDITOR_PLUGIN_VERSION"
CKEDITOR_PLUGIN_COLORBUTTON_VERSION="$CKEDITOR_PLUGIN_VERSION"
CKEDITOR_PLUGIN_PANELBUTTON_VERSION="$CKEDITOR_PLUGIN_VERSION"

get_ckeditor_library(){
  lib_name="$1"
  lib_version="$2"
  (
    cd /tmp
    wget "https://download.ckeditor.com/${lib_name}/releases/${lib_name}_${lib_version}.zip" -O "${lib_name}.zip"
    unzip "${lib_name}.zip"; rm "${lib_name}.zip"
  )
  mv "/tmp/${lib_name}" "web/libraries/${lib_name}"
}

get_ckeditor_library codesnippet "$CKEDITOR_PLUGIN_CODESNIPPET_VERSION"
get_ckeditor_library colorbutton "$CKEDITOR_PLUGIN_COLORBUTTON_VERSION"
get_ckeditor_library panelbutton "$CKEDITOR_PLUGIN_PANELBUTTON_VERSION"
