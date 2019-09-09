# Patches for core

patch -p1 < /mnt/data/upgrade/d8_cern_patches/multisite-modules-themes-install-2718105-3.patch 
    [https://www.drupal.org/node/2718105] Install sites' modules in sites' directories
    Added 2016-05-17 msalwero
    OK
	
patch -p1 < /mnt/data/upgrade/d8_cern_patches/allow_tokens_in_text_editor-2830210-14.patch
    [DRUPAL-302] [https://www.drupal.org/project/drupal/issues/2830210]
	Allow tokens to be used in Text Editor image upload directory path
    OK

patch -p1 < /mnt/data/upgrade/d8_cern_patches/path-new_translation_draft_alias-2930599-31-complete.patch
    Fix for [CERN 904] [https://steps.everis.com/jiraproy/browse/CERN-904]
    From https://www.drupal.org/project/drupal/issues/2930599#comment-12779330
    OK


# Patches for centrally managed modules

patch -d modules/simplesamlphp_auth -p1 < /mnt/data/upgrade/d8_cern_patches/2894327-reevaluate_roles-5.patch
    [DRUPAL-183] [https://www.drupal.org/node/2894327] Reevaluate roles every time the user logs in does not remove roles
    Added 2017-11-09
    OK

colorbutton-2919123.patch
patch -d modules/colorbutton -p1 < /mnt/data/upgrade/d8_cern_patches/colorbutton-2919123.patch
    [https://www.drupal.org/project/colorbutton/issues/2919123]
    file_get_contents after clear cache and access node/add/* 
    Warning: file_get_contents(/libraries/colorbutton/plugin.js): failed to open stream: No such file or directory in _locale_parse_js_file() (line 1134 of core/modules/locale/locale.module). _locale_parse_js_file('/libraries/colorbutton/plugin.js')...
    OK

patch -d modules/ckeditor_font/ -p1 < /mnt/data/upgrade/d8_cern_patches/ckeditor_font-2729087-lib-issue-12.patch
   Custom adapted version of this change, https://www.drupal.org/node/2729087
   similar problem as colorbutton-2919123.patch
   OK


patch -d modules/panelbutton/ -p1 < /mnt/data/upgrade/d8_cern_patches/panelbutton-2756597.patch
  [https://www.drupal.org/project/panelbutton/issues/2756597]  
  similar error as colorbutton and ckeditor_font
  OK

patch -d modules/migrate_upgrade/ -p1 < /mnt/data/upgrade/d8_cern_patches/Migrate_upgrade_rc5_seem_not_to_work-29196962-6.patch
patch -d modules/migrate_upgrade/ -p1 < /mnt/data/upgrade/d8_cern_patches/legacy-db-prefix_option_not_\ working_correct-2999099-003.patch
  [https://www.drupal.org/project/migrate_upgrade/issues/2996962#comment-12787259]
  OK
