<?php

namespace Drupal\cern_cds_media\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "cerncdsmedia" plugin.
 *
 * @CKEditorPlugin(
 *   id = "cerncdsmedia",
 *   label = @Translation("CernCdsMedia"),
 *   module = "cern_cds_media"
 * )
 */
class CernCdsMedia extends CKEditorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'cern_cds_media') . '/plugins/cerncdsmedia/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    $path = drupal_get_path('module', 'cern_cds_media') . '/';
    return [
      'CernCdsMedia' => [
        'label' => $this->t('CERN CDS Media'),
        'image' => $path . 'plugins/cerncdsmedia/images/cds.png',
      ],
    ];
  }

}
