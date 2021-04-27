<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * To require it's presence, you can require `composer-runtime-api ^2.0`
 */
class InstalledVersions
{
    private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '6523c0004d7ed3ca070840ddb5c30148b96dc274',
    'name' => 'drupal/recommended-project',
  ),
  'versions' => 
  array (
    'asm89/stack-cors' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b9c31def6a83f84b4d4a40d35996d375755f0e08',
    ),
    'composer/installers' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1a0357fccad9d1cc1ea0c9a05b8847fbccccb78d',
    ),
    'composer/semver' => 
    array (
      'pretty_version' => '1.5.1',
      'version' => '1.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c6bea70230ef4dd483e6bbcab6005f682ed3a8de',
    ),
    'consolidation/annotated-command' => 
    array (
      'pretty_version' => '2.12.1',
      'version' => '2.12.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '0ee361762df2274f360c085e3239784a53f850b5',
    ),
    'consolidation/output-formatters' => 
    array (
      'pretty_version' => '3.5.1',
      'version' => '3.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '0d38f13051ef05c223a2bb8e962d668e24785196',
    ),
    'cweagans/composer-patches' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ae02121445ad75f4eaff800cc532b5e6233e2ddf',
    ),
    'dflydev/dot-access-data' => 
    array (
      'pretty_version' => 'v1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '3fbd874921ab2c041e899d044585a2ab9795df8a',
    ),
    'dnoegel/php-xdg-base-dir' => 
    array (
      'pretty_version' => 'v0.1.1',
      'version' => '0.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8f8a6e48c5ecb0f991c2fdcf5f154a47d85f9ffd',
    ),
    'doctrine/annotations' => 
    array (
      'pretty_version' => 'v1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '54cacc9b81758b14e3ce750f205a393d52339e97',
    ),
    'doctrine/cache' => 
    array (
      'pretty_version' => 'v1.6.2',
      'version' => '1.6.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eb152c5100571c7a45470ff2a35095ab3f3b900b',
    ),
    'doctrine/collections' => 
    array (
      'pretty_version' => 'v1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1a4fb7e902202c33cce8c55989b945612943c2ba',
    ),
    'doctrine/common' => 
    array (
      'pretty_version' => 'v2.7.3',
      'version' => '2.7.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '4acb8f89626baafede6ee5475bc5844096eba8a9',
    ),
    'doctrine/inflector' => 
    array (
      'pretty_version' => 'v1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e11d84c6e018beedd929cff5220969a3c6d1d462',
    ),
    'doctrine/lexer' => 
    array (
      'pretty_version' => '1.0.2',
      'version' => '1.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '1febd6c3ef84253d7c815bed85fc622ad207a9f8',
    ),
    'dompdf/dompdf' => 
    array (
      'pretty_version' => 'v0.8.6',
      'version' => '0.8.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'db91d81866c69a42dad1d2926f61515a1e3f42c5',
    ),
    'drupal-ckeditor-libraries-group/codesnippet' => 
    array (
      'pretty_version' => '4.16.0',
      'version' => '4.16.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cbddc36e5873323b1c34db1542a3449bcb1ff9e7',
    ),
    'drupal-ckeditor-libraries-group/font' => 
    array (
      'pretty_version' => '4.13.1',
      'version' => '4.13.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '81cbe90726319bb0a8c0c12c0a893e1e2e660c60',
    ),
    'drupal/action' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/admin_toolbar' => 
    array (
      'pretty_version' => '2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.4',
    ),
    'drupal/adminimal_theme' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/aggregator' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/allowed_formats' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/automated_cron' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/ban' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/bartik' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/basic_auth' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/big_pipe' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/block' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/block_content' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/block_place' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/book' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/bootstrap' => 
    array (
      'pretty_version' => '3.23.0',
      'version' => '3.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.23',
    ),
    'drupal/breakpoint' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/captcha' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/cern-adminimal-subtheme' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a2ed5628d532fccb54abbeaf063db3944784bd76',
    ),
    'drupal/cern-base-theme' => 
    array (
      'pretty_version' => '2.6.6',
      'version' => '2.6.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cb5ed2e618d84b10f0f7c4441ad0be5ce0a2c2d3',
    ),
    'drupal/cern-cds-media' => 
    array (
      'pretty_version' => '2.1.4',
      'version' => '2.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '211fb1be8dbb83df9dd791b6f75d8798bb99f290',
    ),
    'drupal/cern-components' => 
    array (
      'pretty_version' => '2.7.10',
      'version' => '2.7.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '8cc5c011dbb200e748718701212882dcda5563ff',
    ),
    'drupal/cern-dev-status' => 
    array (
      'pretty_version' => '2.0.5',
      'version' => '2.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'faba2b40d7f34d718f0826dbb38250e42881e71a',
    ),
    'drupal/cern-display-formats' => 
    array (
      'pretty_version' => '1.4.5',
      'version' => '1.4.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '57b5b7cc346f4094359efbb53f9cb21cfedbaaf3',
    ),
    'drupal/cern-drupal-welcome-message-block' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c5a6c1b0b465429ffe2c3d5bac92b6fa39f668f6',
    ),
    'drupal/cern-full-html-format' => 
    array (
      'pretty_version' => '2.0.4',
      'version' => '2.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '4e4a15aa77576abadb8639f537e31d31edbc09a6',
    ),
    'drupal/cern-indico-feeds' => 
    array (
      'pretty_version' => '2.0.5',
      'version' => '2.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '8478ce54ec7267c82e3370c5e35642d8e0c11534',
    ),
    'drupal/cern-landing-page' => 
    array (
      'pretty_version' => '2.2.4',
      'version' => '2.2.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a6aa2903393d64b341900aa65c3e160afd39752f',
    ),
    'drupal/cern-loading' => 
    array (
      'pretty_version' => '2.1.2',
      'version' => '2.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '7192a53bc4b2d6a4187c3abf1fef6df1d1df1744',
    ),
    'drupal/cern-paragraph-types' => 
    array (
      'pretty_version' => '2.2.3',
      'version' => '2.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '5d9bd43e424dc7865ccd564544a1e07045f5c111',
    ),
    'drupal/cern-profile-displayname' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1392ffebc377e627a22b6a817f2c342d126b93f2',
    ),
    'drupal/cern-theme' => 
    array (
      'pretty_version' => '2.6.11',
      'version' => '2.6.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '38332c5687938b4c9368a05693071c884da02d14',
    ),
    'drupal/cern-toolbar' => 
    array (
      'pretty_version' => '2.2.3',
      'version' => '2.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ab38a97568bfe8e7f985bde28fdfd847b89aa685',
    ),
    'drupal/cern-webcast-feeds' => 
    array (
      'pretty_version' => '2.0.4',
      'version' => '2.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '4f255383eec3c97d2b9de1c0bae98d23b16e0076',
    ),
    'drupal/ckeditor' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/ckeditor_font' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/claro' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/classy' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/codesnippet' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/color' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/color_field' => 
    array (
      'pretty_version' => '2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.4',
    ),
    'drupal/colorbox' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/colorbutton' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.2',
    ),
    'drupal/comment' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/components' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/config' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/config_translation' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/config_update' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/contact' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/content_access' => 
    array (
      'pretty_version' => '1.0.0-alpha3',
      'version' => '1.0.0.0-alpha3',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-alpha3',
    ),
    'drupal/content_moderation' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/content_translation' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/context' => 
    array (
      'pretty_version' => '4.0.0-beta5',
      'version' => '4.0.0.0-beta5',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.0-beta5',
    ),
    'drupal/contextual' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/contribute' => 
    array (
      'pretty_version' => '1.0.0-beta8',
      'version' => '1.0.0.0-beta8',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-beta8',
    ),
    'drupal/cookieconsent' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/core' => 
    array (
      'pretty_version' => '8.9.13',
      'version' => '8.9.13.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a53db77b55a035453d7229e0c3069f8591cb4cb6',
    ),
    'drupal/core-annotation' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-assertion' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-bridge' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-class-finder' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-composer-scaffold' => 
    array (
      'pretty_version' => '8.9.13',
      'version' => '8.9.13.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c902d07cb49ef73777e2b33a39e54c2861a8c81d',
    ),
    'drupal/core-datetime' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-dependency-injection' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-diff' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-discovery' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-event-dispatcher' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-file-cache' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-file-security' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-filesystem' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-gettext' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-graph' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-http-foundation' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-php-storage' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-plugin' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-project-message' => 
    array (
      'pretty_version' => '8.9.13',
      'version' => '8.9.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '3f8fa28128f1fef68ee0e6647011a543ef92be5b',
    ),
    'drupal/core-proxy-builder' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-recommended' => 
    array (
      'pretty_version' => '8.9.13',
      'version' => '8.9.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '7a940fd5b64d2b22366680e2a60d96bf2c10089d',
    ),
    'drupal/core-render' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-serialization' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-transliteration' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-utility' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-uuid' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/core-version' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/ctools' => 
    array (
      'pretty_version' => '3.4.0',
      'version' => '3.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.4',
    ),
    'drupal/ctools_block' => 
    array (
      'pretty_version' => '3.4.0',
      'version' => '3.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'drupal/datetime' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/datetime_range' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/dblog' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/devel' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.1',
    ),
    'drupal/domain_301_redirect' => 
    array (
      'pretty_version' => '1.0.0-alpha0',
      'version' => '1.0.0.0-alpha0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-alpha0',
    ),
    'drupal/ds' => 
    array (
      'pretty_version' => '3.12.0',
      'version' => '3.12.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.12',
    ),
    'drupal/dynamic_page_cache' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/easy_breadcrumb' => 
    array (
      'pretty_version' => '1.15.0',
      'version' => '1.15.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.15',
    ),
    'drupal/easychart' => 
    array (
      'pretty_version' => '3.4.0',
      'version' => '3.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.4',
    ),
    'drupal/editor' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/entity_browser' => 
    array (
      'pretty_version' => '2.5.0',
      'version' => '2.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.5',
    ),
    'drupal/entity_print' => 
    array (
      'pretty_version' => '2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.2',
    ),
    'drupal/entity_reference' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/entity_reference_revisions' => 
    array (
      'pretty_version' => '1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.9',
    ),
    'drupal/externalauth' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/extlink' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/facets' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/fast_404' => 
    array (
      'pretty_version' => '2.0.0-alpha5',
      'version' => '2.0.0.0-alpha5',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.0-alpha5',
    ),
    'drupal/features' => 
    array (
      'pretty_version' => '3.12.0',
      'version' => '3.12.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.12',
    ),
    'drupal/feeds' => 
    array (
      'pretty_version' => '3.0.0-alpha10',
      'version' => '3.0.0.0-alpha10',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.0-alpha10',
    ),
    'drupal/fences' => 
    array (
      'pretty_version' => '2.0.0-rc1',
      'version' => '2.0.0.0-RC1',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.0-rc1',
    ),
    'drupal/field' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/field_formatter_class' => 
    array (
      'pretty_version' => '1.5.0',
      'version' => '1.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.5',
    ),
    'drupal/field_group' => 
    array (
      'pretty_version' => '3.1.0',
      'version' => '3.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.1',
    ),
    'drupal/field_layout' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/field_permissions' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/field_ui' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/file' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/file_mdm' => 
    array (
      'pretty_version' => 'dev-1.x',
      'version' => 'dev-1.x',
      'aliases' => 
      array (
        0 => '1.x-dev',
      ),
      'reference' => '30264f78b6c6f98a614e2da8d1e0c843dce59472',
    ),
    'drupal/file_mdm_exif' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'drupal/filefield_paths' => 
    array (
      'pretty_version' => '1.0.0-beta5',
      'version' => '1.0.0.0-beta5',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-beta5',
    ),
    'drupal/filter' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/forum' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/hal' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/help' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/help_topics' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/history' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/honeypot' => 
    array (
      'pretty_version' => '1.30.0',
      'version' => '1.30.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.30',
    ),
    'drupal/hook_event_dispatcher' => 
    array (
      'pretty_version' => '1.29.0',
      'version' => '1.29.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.29',
    ),
    'drupal/hotjar' => 
    array (
      'pretty_version' => '2.0.0',
      'version' => '2.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.0',
    ),
    'drupal/image' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/imagemagick' => 
    array (
      'pretty_version' => '2.7.0',
      'version' => '2.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.7',
    ),
    'drupal/imce' => 
    array (
      'pretty_version' => '2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.3',
    ),
    'drupal/inline_form_errors' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/jquery_ui' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.4',
    ),
    'drupal/jquery_ui_draggable' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.2',
    ),
    'drupal/jquery_ui_droppable' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.2',
    ),
    'drupal/jsonapi' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/kint' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'drupal/language' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/layout_builder' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/layout_discovery' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/link' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/locale' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/ludwig' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/mailsystem' => 
    array (
      'pretty_version' => '4.3.0',
      'version' => '4.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.3',
    ),
    'drupal/matomo' => 
    array (
      'pretty_version' => '1.11.0',
      'version' => '1.11.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.11',
    ),
    'drupal/media' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/media_library' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/memcache' => 
    array (
      'pretty_version' => '2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.3',
    ),
    'drupal/menu_block' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/menu_breadcrumb' => 
    array (
      'pretty_version' => '1.14.0',
      'version' => '1.14.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.14',
    ),
    'drupal/menu_force' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.2',
    ),
    'drupal/menu_link_content' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/menu_ui' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/metatag' => 
    array (
      'pretty_version' => '1.16.0',
      'version' => '1.16.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.16',
    ),
    'drupal/migrate' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/migrate_devel' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.4',
    ),
    'drupal/migrate_drupal' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/migrate_drupal_multilingual' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/migrate_drupal_ui' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/migrate_plus' => 
    array (
      'pretty_version' => '4.2.0',
      'version' => '4.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.2',
    ),
    'drupal/migrate_tools' => 
    array (
      'pretty_version' => '4.5.0',
      'version' => '4.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.5',
    ),
    'drupal/migrate_upgrade' => 
    array (
      'pretty_version' => '3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.2',
    ),
    'drupal/minimal' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/module_filter' => 
    array (
      'pretty_version' => '3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.2',
    ),
    'drupal/node' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/node_view_permissions' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.4',
    ),
    'drupal/openid_connect' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0',
    ),
    'drupal/options' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/page_cache' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/panelbutton' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/panelizer' => 
    array (
      'pretty_version' => '4.4.0',
      'version' => '4.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.4',
    ),
    'drupal/panels' => 
    array (
      'pretty_version' => '4.6.0',
      'version' => '4.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.6',
    ),
    'drupal/panels_ipe' => 
    array (
      'pretty_version' => '4.6.0',
      'version' => '4.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'drupal/paragraphs' => 
    array (
      'pretty_version' => '1.12.0',
      'version' => '1.12.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.12',
    ),
    'drupal/path' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/path_alias' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/pathauto' => 
    array (
      'pretty_version' => '1.8.0',
      'version' => '1.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.8',
    ),
    'drupal/permissions_by_term' => 
    array (
      'pretty_version' => '2.31.0',
      'version' => '2.31.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.31',
    ),
    'drupal/piwik' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.4',
    ),
    'drupal/quickedit' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/rdf' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/recaptcha' => 
    array (
      'pretty_version' => '2.5.0',
      'version' => '2.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.5',
    ),
    'drupal/recommended-project' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '6523c0004d7ed3ca070840ddb5c30148b96dc274',
    ),
    'drupal/redirect' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/require_login' => 
    array (
      'pretty_version' => '2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.4',
    ),
    'drupal/responsive_image' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/rest' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/rules' => 
    array (
      'pretty_version' => '3.0.0-alpha6',
      'version' => '3.0.0.0-alpha6',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.0-alpha6',
    ),
    'drupal/scheduler' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/search' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/search_api' => 
    array (
      'pretty_version' => '1.19.0',
      'version' => '1.19.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.19',
    ),
    'drupal/serialization' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/settings_tray' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/seven' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/shortcut' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/simplesamlphp_auth' => 
    array (
      'pretty_version' => '3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-3.2',
    ),
    'drupal/simpletest' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/smart_trim' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/standard' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/stark' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/statistics' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/sticky' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/syslog' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/system' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/taxonomy' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/telephone' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/text' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/token' => 
    array (
      'pretty_version' => '1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.9',
    ),
    'drupal/toolbar' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/tour' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/tracker' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/twig_tweak' => 
    array (
      'pretty_version' => '2.9.0',
      'version' => '2.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.9',
    ),
    'drupal/typed_data' => 
    array (
      'pretty_version' => 'dev-1.x',
      'version' => 'dev-1.x',
      'aliases' => 
      array (
        0 => '1.x-dev',
      ),
      'reference' => '27555f47b522730d04f3b33c9a46c0acbcb3146e',
    ),
    'drupal/ui_patterns' => 
    array (
      'pretty_version' => '1.2.0',
      'version' => '1.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.2',
    ),
    'drupal/update' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/user' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/userprotect' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/views' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/views_bulk_operations' => 
    array (
      'pretty_version' => '2.6.0',
      'version' => '2.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-2.6',
    ),
    'drupal/views_slideshow' => 
    array (
      'pretty_version' => '4.8.0',
      'version' => '4.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-4.8',
    ),
    'drupal/views_ui' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/viewsreference' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.7',
    ),
    'drupal/webform' => 
    array (
      'pretty_version' => '5.25.0',
      'version' => '5.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-5.25',
    ),
    'drupal/webform_analysis' => 
    array (
      'pretty_version' => '1.0.0-beta7',
      'version' => '1.0.0.0-beta7',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-beta7',
    ),
    'drupal/webform_invitation' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.1',
    ),
    'drupal/webform_ui' => 
    array (
      'pretty_version' => '5.25.0',
      'version' => '5.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'drupal/workbench' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.3',
    ),
    'drupal/workbench_access' => 
    array (
      'pretty_version' => '1.0.0-beta4',
      'version' => '1.0.0.0-beta4',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.0-beta4',
    ),
    'drupal/workbench_moderation' => 
    array (
      'pretty_version' => '1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8.x-1.6',
    ),
    'drupal/workflows' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drupal/workspaces' => 
    array (
      'replaced' => 
      array (
        0 => '8.9.13',
      ),
    ),
    'drush/drush' => 
    array (
      'pretty_version' => '8.4.8',
      'version' => '8.4.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b377b1896e344085d06bdbf671a465950a164d1e',
    ),
    'easyrdf/easyrdf' => 
    array (
      'pretty_version' => '0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'acd09dfe0555fbcfa254291e433c45fdd4652566',
    ),
    'egulias/email-validator' => 
    array (
      'pretty_version' => '2.1.17',
      'version' => '2.1.17.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ade6887fd9bd74177769645ab5c474824f8a418a',
    ),
    'fileeye/mimemap' => 
    array (
      'pretty_version' => '1.1.4',
      'version' => '1.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '3a0ddb71f06d8fb3f84f0a3c45348af81803b16d',
    ),
    'gettext/gettext' => 
    array (
      'pretty_version' => 'v4.8.4',
      'version' => '4.8.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '58bc0f7f37e78efb0f9758f93d4a0f669f0f84a1',
    ),
    'gettext/languages' => 
    array (
      'pretty_version' => '2.6.0',
      'version' => '2.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '38ea0482f649e0802e475f0ed19fa993bcb7a618',
    ),
    'guzzlehttp/guzzle' => 
    array (
      'pretty_version' => '6.5.4',
      'version' => '6.5.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a4a1b6930528a8f7ee03518e6442ec7a44155d9d',
    ),
    'guzzlehttp/promises' => 
    array (
      'pretty_version' => 'v1.3.1',
      'version' => '1.3.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a59da6cf61d80060647ff4d3eb2c03a2bc694646',
    ),
    'guzzlehttp/psr7' => 
    array (
      'pretty_version' => '1.6.1',
      'version' => '1.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '239400de7a173fe9901b9ac7c06497751f00727a',
    ),
    'laminas/laminas-diactoros' => 
    array (
      'pretty_version' => '1.8.7p2',
      'version' => '1.8.7.0-patch2',
      'aliases' => 
      array (
      ),
      'reference' => '6991c1af7c8d2c8efee81b22ba97024781824aaa',
    ),
    'laminas/laminas-escaper' => 
    array (
      'pretty_version' => '2.6.1',
      'version' => '2.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '25f2a053eadfa92ddacb609dcbbc39362610da70',
    ),
    'laminas/laminas-feed' => 
    array (
      'pretty_version' => '2.12.2',
      'version' => '2.12.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '8a193ac96ebcb3e16b6ee754ac2a889eefacb654',
    ),
    'laminas/laminas-stdlib' => 
    array (
      'pretty_version' => '3.2.1',
      'version' => '3.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2b18347625a2f06a1a485acfbc870f699dbe51c6',
    ),
    'laminas/laminas-zendframework-bridge' => 
    array (
      'pretty_version' => '1.0.4',
      'version' => '1.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fcd87520e4943d968557803919523772475e8ea3',
    ),
    'lsolesen/pel' => 
    array (
      'pretty_version' => '0.9.6',
      'version' => '0.9.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c9e3919f5db3b85c3c422d4f8d448dbcb2a87a23',
    ),
    'masterminds/html5' => 
    array (
      'pretty_version' => '2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2c37c6c520b995b761674de3be8455a381679067',
    ),
    'nikic/php-parser' => 
    array (
      'pretty_version' => 'v4.10.4',
      'version' => '4.10.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c6d052fc58cb876152f89f532b95a8d7907e7f0e',
    ),
    'paragonie/random_compat' => 
    array (
      'pretty_version' => 'v9.99.99',
      'version' => '9.99.99.0',
      'aliases' => 
      array (
      ),
      'reference' => '84b4dfb120c6f9b4ff7b3685f9b8f1aa365a0c95',
    ),
    'pear/archive_tar' => 
    array (
      'pretty_version' => '1.4.12',
      'version' => '1.4.12.0',
      'aliases' => 
      array (
      ),
      'reference' => '19bb8e95490d3e3ad92fcac95500ca80bdcc7495',
    ),
    'pear/console_getopt' => 
    array (
      'pretty_version' => 'v1.4.3',
      'version' => '1.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a41f8d3e668987609178c7c4a9fe48fecac53fa0',
    ),
    'pear/console_table' => 
    array (
      'pretty_version' => 'v1.3.1',
      'version' => '1.3.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '1930c11897ca61fd24b95f2f785e99e0f36dcdea',
    ),
    'pear/pear-core-minimal' => 
    array (
      'pretty_version' => 'v1.10.10',
      'version' => '1.10.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '625a3c429d9b2c1546438679074cac1b089116a7',
    ),
    'pear/pear_exception' => 
    array (
      'pretty_version' => 'v1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dbb42a5a0e45f3adcf99babfb2a1ba77b8ac36a7',
    ),
    'phenx/php-font-lib' => 
    array (
      'pretty_version' => '0.5.2',
      'version' => '0.5.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ca6ad461f032145fff5971b5985e5af9e7fa88d8',
    ),
    'phenx/php-svg-lib' => 
    array (
      'pretty_version' => 'v0.3.3',
      'version' => '0.3.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '5fa61b65e612ce1ae15f69b3d223cb14ecc60e32',
    ),
    'phpfastcache/riak-client' => 
    array (
      'pretty_version' => '3.4.3',
      'version' => '3.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd771f75d16196006604a30bb15adc1c6a9b0fcc9',
    ),
    'phpmailer/phpmailer' => 
    array (
      'pretty_version' => 'v6.3.0',
      'version' => '6.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4a08cf4cdd2c38d12ee2b9fa69e5d235f37a6dcb',
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f73288fd15629204f9d42b7055f72dacbe811fc',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psy/psysh' => 
    array (
      'pretty_version' => 'v0.10.7',
      'version' => '0.10.7.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a395af46999a12006213c0c8346c9445eb31640c',
    ),
    'ralouphie/getallheaders' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '120b605dfeb996808c31b6477290a714d356e822',
    ),
    'robrichards/xmlseclibs' => 
    array (
      'pretty_version' => '3.1.1',
      'version' => '3.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f8f19e58f26cdb42c54b214ff8a820760292f8df',
    ),
    'roundcube/plugin-installer' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'rsky/pear-core-min' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.10.10',
      ),
    ),
    'sabberworm/php-css-parser' => 
    array (
      'pretty_version' => '8.3.1',
      'version' => '8.3.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd217848e1396ef962fb1997cf3e2421acba7f796',
    ),
    'shama/baton' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'simplesamlphp/composer-module-installer' => 
    array (
      'pretty_version' => 'v1.1.8',
      'version' => '1.1.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '45161b5406f3e9c82459d0f9a5a1dba064953cfa',
    ),
    'simplesamlphp/saml2' => 
    array (
      'pretty_version' => 'v4.2.0',
      'version' => '4.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd4038b83be50ccd64ecdc0b7c68e66d63c899d2c',
    ),
    'simplesamlphp/simplesamlphp' => 
    array (
      'pretty_version' => 'v1.18.8',
      'version' => '1.18.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ebb6d15bb8e8b45504adc26fd3872073d1e5cd9b',
    ),
    'simplesamlphp/simplesamlphp-module-adfs' => 
    array (
      'pretty_version' => 'v0.9.6',
      'version' => '0.9.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '425e5ebbdd097c92fe5265a6b48d32a3095c7237',
    ),
    'simplesamlphp/simplesamlphp-module-authcrypt' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9a2c1a761e2d94394a4f2d3499fd6f0853899530',
    ),
    'simplesamlphp/simplesamlphp-module-authfacebook' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9152731e939ad4a49e0f06da5f0009ebde0d2b5c',
    ),
    'simplesamlphp/simplesamlphp-module-authorize' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0593bfcb84fca9d9133f415246ab8ca51b412c92',
    ),
    'simplesamlphp/simplesamlphp-module-authtwitter' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '29a15e58061222632fea9eb2c807aef5e2c0d54a',
    ),
    'simplesamlphp/simplesamlphp-module-authwindowslive' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f40aecec6c0adaedb6693309840c98cec783876e',
    ),
    'simplesamlphp/simplesamlphp-module-authx509' => 
    array (
      'pretty_version' => 'v0.9.8',
      'version' => '0.9.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '66525b1ec4145ec8d0d0e9db4534624b6be4c1fb',
    ),
    'simplesamlphp/simplesamlphp-module-authyubikey' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8c27bfeb4981d2e6fa40a831e945f40c5a4ad3d2',
    ),
    'simplesamlphp/simplesamlphp-module-cas' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '63b72e4600550c507cdfc32fdd208ad59a64321e',
    ),
    'simplesamlphp/simplesamlphp-module-cdc' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '16a5bfac7299e04e5feb472af328e07598708166',
    ),
    'simplesamlphp/simplesamlphp-module-consent' => 
    array (
      'pretty_version' => 'v0.9.6',
      'version' => '0.9.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '2f84d15e96afb5a32b6d1cff93370f501ca7867d',
    ),
    'simplesamlphp/simplesamlphp-module-consentadmin' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '466e8d0d751f0080162d78e63ab2e125b24d17a1',
    ),
    'simplesamlphp/simplesamlphp-module-discopower' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c892926e8186d0a2c638f7032dfc30540c1f92fb',
    ),
    'simplesamlphp/simplesamlphp-module-exampleattributeserver' => 
    array (
      'pretty_version' => 'v1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '63e0323e81c32bc3c9eaa01ea45194bb10153708',
    ),
    'simplesamlphp/simplesamlphp-module-expirycheck' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '59c59cdf87e2679257b46c07bb4c27666a11cc20',
    ),
    'simplesamlphp/simplesamlphp-module-ldap' => 
    array (
      'pretty_version' => 'v0.9.10',
      'version' => '0.9.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '78f04cbe41bfb9dcbcdeff4b5f12e67c060e1a77',
    ),
    'simplesamlphp/simplesamlphp-module-memcachemonitor' => 
    array (
      'pretty_version' => 'v0.9.2',
      'version' => '0.9.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '900b5c6b59913d9013b8dae090841a127ae55ae5',
    ),
    'simplesamlphp/simplesamlphp-module-memcookie' => 
    array (
      'pretty_version' => 'v1.2.2',
      'version' => '1.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '39535304e8d464b7baa1e82cb441fa432947ff57',
    ),
    'simplesamlphp/simplesamlphp-module-metarefresh' => 
    array (
      'pretty_version' => 'v0.9.6',
      'version' => '0.9.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e284306a7097297765b5b78a4e28f19f18d4e001',
    ),
    'simplesamlphp/simplesamlphp-module-negotiate' => 
    array (
      'pretty_version' => 'v0.9.10',
      'version' => '0.9.10.0',
      'aliases' => 
      array (
      ),
      'reference' => 'db05ff40399c66e3f14697a8162da6b2fbdab47d',
    ),
    'simplesamlphp/simplesamlphp-module-oauth' => 
    array (
      'pretty_version' => 'v0.9.2',
      'version' => '0.9.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd14d7aca6e699ec12b3f4dd0128373faa1a2cc61',
    ),
    'simplesamlphp/simplesamlphp-module-preprodwarning' => 
    array (
      'pretty_version' => 'v0.9.2',
      'version' => '0.9.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '8e032de33a75eb44857dc06d886ad94ee3af4638',
    ),
    'simplesamlphp/simplesamlphp-module-radius' => 
    array (
      'pretty_version' => 'v0.9.3',
      'version' => '0.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '36bd0f39f9a13f7eb96ead97c97c3634aa1c3f2d',
    ),
    'simplesamlphp/simplesamlphp-module-riak' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c1a9d9545cb4e05b9205b34624850bb777aca991',
    ),
    'simplesamlphp/simplesamlphp-module-sanitycheck' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '15d6664eae73a233c3c4c72fd8a5c2be72b6ed2a',
    ),
    'simplesamlphp/simplesamlphp-module-smartattributes' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b45d3ecd916e359a9cae05f9ae9df09b5c42f4e6',
    ),
    'simplesamlphp/simplesamlphp-module-sqlauth' => 
    array (
      'pretty_version' => 'v0.9.1',
      'version' => '0.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '31bce8763ad97f4b4473e4ad4a5a96ddc136ef6b',
    ),
    'simplesamlphp/simplesamlphp-module-statistics' => 
    array (
      'pretty_version' => 'v0.9.6',
      'version' => '0.9.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '03fb6bdbbf5ce0a0cb257208db79aacac227ac10',
    ),
    'simplesamlphp/twig-configurable-i18n' => 
    array (
      'pretty_version' => 'v2.3.4',
      'version' => '2.3.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e2bffc7eed3112a0b3870ef5b4da0fd74c7c4b8a',
    ),
    'squizlabs/php_codesniffer' => 
    array (
      'pretty_version' => '3.5.8',
      'version' => '3.5.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '9d583721a7157ee997f235f327de038e7ea6dac4',
    ),
    'stack/builder' => 
    array (
      'pretty_version' => 'v1.0.5',
      'version' => '1.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fb3d136d04c6be41120ebf8c0cc71fe9507d750a',
    ),
    'symfony-cmf/routing' => 
    array (
      'pretty_version' => '1.4.1',
      'version' => '1.4.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fb1e7f85ff8c6866238b7e73a490a0a0243ae8ac',
    ),
    'symfony/class-loader' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e4636a4f23f157278a19e5db160c63de0da297d8',
    ),
    'symfony/config' => 
    array (
      'pretty_version' => 'v4.4.20',
      'version' => '4.4.20.0',
      'aliases' => 
      array (
      ),
      'reference' => '98606c6fa1a8f55ff964ccdd704275bf5b9f71b3',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bfe29ead7e7b1cc9ce74c6a40d06ad1f96fced13',
    ),
    'symfony/debug' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '518c6a00d0872da30bd06aee3ea59a0a5cf54d6d',
    ),
    'symfony/dependency-injection' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e39380b7104b0ec538a075ae919f00c7e5267bac',
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '14d978f8e8555f2de719c00eb65376be7d2e9081',
    ),
    'symfony/filesystem' => 
    array (
      'pretty_version' => 'v5.2.4',
      'version' => '5.2.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '710d364200997a5afde34d9fe57bd52f3cc1e108',
    ),
    'symfony/finder' => 
    array (
      'pretty_version' => 'v4.4.20',
      'version' => '4.4.20.0',
      'aliases' => 
      array (
      ),
      'reference' => '2543795ab1570df588b9bbd31e1a2bd7037b94f6',
    ),
    'symfony/http-foundation' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fbd216d2304b1a3fe38d6392b04729c8dd356359',
    ),
    'symfony/http-kernel' => 
    array (
      'pretty_version' => 'v3.4.44',
      'version' => '3.4.44.0',
      'aliases' => 
      array (
      ),
      'reference' => '27dcaa8c6b18c75df9f37badeb4d3564ffaa1326',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e94c8b1bbe2bc77507a1056cdb06451c75b427f9',
    ),
    'symfony/polyfill-iconv' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c4de7601eefbf25f9d47190abe07f79fe0a27424',
    ),
    'symfony/polyfill-intl-idn' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '3bff59ea7047e925be6b7f2059d60af31bb46d6a',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fa79b11539418b02fc5e1897267673ba2c19419c',
    ),
    'symfony/polyfill-php56' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e3c8c138280cdfe4b81488441555583aa1984e23',
    ),
    'symfony/polyfill-php70' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '82225c2d7d23d7e70515496d249c0152679b468e',
    ),
    'symfony/polyfill-php72' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f048e612a3905f34931127360bdd2def19a5e582',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dc3063ba22c2a1fd2f45ed856374d79114998f91',
    ),
    'symfony/polyfill-util' => 
    array (
      'pretty_version' => 'v1.17.0',
      'version' => '1.17.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4afb4110fc037752cf0ce9869f9ab8162c4e20d7',
    ),
    'symfony/process' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '8a895f0c92a7c4b10db95139bcff71bdf66d4d21',
    ),
    'symfony/psr-http-message-bridge' => 
    array (
      'pretty_version' => 'v1.1.2',
      'version' => '1.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a33352af16f78a5ff4f9d90811536abf210df12b',
    ),
    'symfony/routing' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e0d43b6f9417ad59ecaa8e2f799b79eef417387f',
    ),
    'symfony/serializer' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '0db90db012b1b0a04fbb2d64ae9160871cad9d4f',
    ),
    'symfony/translation' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b0cd62ef0ff7ec31b67d78d7fc818e2bda4e844f',
    ),
    'symfony/validator' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '5fb88120a11a75e17b602103a893dd8b27804529',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => 'v4.4.20',
      'version' => '4.4.20.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a1eab2f69906dc83c5ddba4632180260d0ab4f7f',
    ),
    'symfony/yaml' => 
    array (
      'pretty_version' => 'v3.4.41',
      'version' => '3.4.41.0',
      'aliases' => 
      array (
      ),
      'reference' => '7233ac2bfdde24d672f5305f2b3f6b5d741ef8eb',
    ),
    'twig/extensions' => 
    array (
      'pretty_version' => 'v1.5.4',
      'version' => '1.5.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '57873c8b0c1be51caa47df2cdb824490beb16202',
    ),
    'twig/twig' => 
    array (
      'pretty_version' => 'v1.42.5',
      'version' => '1.42.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '87b2ea9d8f6fd014d0621ca089bb1b3769ea3f8e',
    ),
    'typo3/phar-stream-wrapper' => 
    array (
      'pretty_version' => 'v3.1.4',
      'version' => '3.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e0c1b495cfac064f4f5c4bcb6bf67bb7f345ed04',
    ),
    'webflo/drupal-finder' => 
    array (
      'pretty_version' => '1.2.2',
      'version' => '1.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c8e5dbe65caef285fec8057a4c718a0d4138d1ee',
    ),
    'webmozart/assert' => 
    array (
      'pretty_version' => '1.5.0',
      'version' => '1.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '88e6d84706d09a236046d686bbea96f07b3a34f4',
    ),
    'webmozart/path-util' => 
    array (
      'pretty_version' => '2.3.0',
      'version' => '2.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd939f7edc24c9a1bb9c0dee5cb05d8e859490725',
    ),
    'whitehat101/apr1-md5' => 
    array (
      'pretty_version' => 'v1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8b261c9fc0481b4e9fa9d01c6ca70867b5d5e819',
    ),
    'wikimedia/composer-merge-plugin' => 
    array (
      'pretty_version' => 'v2.0.1',
      'version' => '2.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8ca2ed8ab97c8ebce6b39d9943e9909bb4f18912',
    ),
    'zendframework/zend-diactoros' => 
    array (
      'replaced' => 
      array (
        0 => '~1.8.7.0',
      ),
    ),
    'zendframework/zend-escaper' => 
    array (
      'replaced' => 
      array (
        0 => '2.6.1',
      ),
    ),
    'zendframework/zend-feed' => 
    array (
      'replaced' => 
      array (
        0 => '^2.12.0',
      ),
    ),
    'zendframework/zend-stdlib' => 
    array (
      'replaced' => 
      array (
        0 => '3.2.1',
      ),
    ),
  ),
);
    private static $canGetVendors;
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['versions']);
        }


        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @return bool
     */
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'foo/bar', '^2.3')
     *
     * @param VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param string        $packageName
     * @param string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     *
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }

            return implode(' || ', $ranges);
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['pretty_version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }

            return $installed['versions'][$packageName]['reference'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @return array
     * @psalm-return array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['root'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @return array[]
     * @psalm-return array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>}
     */
    public static function getRawData()
    {
        return self::$installed;
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project's autoloader in process,
     * and wants to ensure both projects have access to their version of installed.php.
     *
     * A typical case would be PHPUnit, where it would need to make sure it reads all
     * the data it needs from this class, then call reload() with
     * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
     * the project in which it runs can then also use this class safely, without
     * interference between PHPUnit's dependencies and the project's dependencies.
     *
     * @param  array[] $data A vendor/composer/installed.php data set
     * @return void
     *
     * @psalm-param array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>} $data
     */
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }

    /**
     * @return array[]
     */
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
        }

        $installed = array();

        if (self::$canGetVendors) {
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (is_file($vendorDir.'/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
                }
            }
        }

        $installed[] = self::$installed;

        return $installed;
    }
}
