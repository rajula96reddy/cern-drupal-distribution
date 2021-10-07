Prepare sites folder
-------------------------------------------------------------------------------
# mv sites /tmp/
# ln -s ../sites .


Patches for core
-------------------------------------------------------------------------------
patch -p1 < /mnt/data/upgrade/d8_cern_patches/allow_tokens_in_text_editor-2830210-14.patch
    [DRUPAL-302] [https://www.drupal.org/project/drupal/issues/2830210]
    Allow tokens to be used in Text Editor image upload directory path
    OK

patch -p1 < /mnt/data/upgrade/d8_cern_patches/drupal_views_argument_validator_TermName-2494617-99.patch
    [DRUPAL-496][https://www.drupal.org/project/drupal/issues/2494617]
    TermName views argument_validator is not working as expected
    OK

patch -p1 < /mnt/data/upgrade/d8_cern_patches/2816447-32.patch
    [DRUPAL-499][https://www.drupal.org/project/drupal/issues/2816447]
    Get feed title in correct language
    OK

patch -p1 < /mnt/data/upgrade/d8_cern_patches/views-rss-fields-enclosure-2511878-35.patch
    [DRUPAL-503][https://www.drupal.org/project/drupal/issues/2511878]
    Support enclosure field in Views RssFields row plugin
    OK

patch -p1 < /mnt/data/upgrade/d8_cern_patches/translations_not_save_invalid_path-3101344-20.patch
    [DRUPAL-567][https://www.drupal.org/project/drupal/issues/3101344]
    Cannot save translated nodes after upgrading to 8.8 due to invalid path
    OK

Patches for centrally managed modules
-------------------------------------------------------------------------------

patch -d modules/ckeditor_font/ -p1 < /mnt/data/upgrade/d8_cern_patches/ckeditor_font-2729087-lib-issue-12.patch
   Custom adapted version of this change, https://www.drupal.org/node/2729087
   similar problem as colorbutton-2919123.patch
   OK

patch -d modules/panelbutton/ -p1 < /mnt/data/upgrade/d8_cern_patches/panelbutton-2756597.patch
  [https://www.drupal.org/project/panelbutton/issues/2756597]
  similar error as colorbutton and ckeditor_font
  OK

patch -d modules/paragraphs -p1 < /mnt/data/upgrade/d8_cern_patches/experimental-widget-asymetric-translation-2904705-47.patch
  [https://www.drupal.org/project/paragraphs/issues/2904705] [DRUPAL-466]
  adds the widget that fixes the landing pages issues
  # WARNING: there is a new version of this patch upstream for PHP7.2 with some community validation.
  # But since this version still applies cleanly, we wont update it yet.
