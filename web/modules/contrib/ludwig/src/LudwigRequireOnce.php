<?php

namespace Drupal\ludwig;

/**
 * Provides service for ludwig_require_once calls.
 *
 * 'classmap' and 'files' libraries are not supported by Ludwig
 * automatically. Contrib modules can take advantage of this
 * Ludwig service to require necessary files manually inside their
 * .module and/or .install files.
 */
class LudwigRequireOnce {

  /**
   * The class caller file directory name.
   *
   * @var string
   */
  protected $dirName;

  /**
   * LudwigRequireOnce class constructor.
   */
  public function __construct() {
    $backtrace = debug_backtrace();
    $this->dirName = dirname($backtrace[3]['file']);
  }

  /**
   * Helper require_once function for Ludwig integration.
   *
   * @param string $package_name
   *   The package name.
   * @param string $file_to_require
   *   The file to require.
   */
  public function ludwigRequireOnce($package_name, $file_to_require) {
    if (!empty($this->dirName)) {
      $ludwig_json = $this->dirName . '/ludwig.json';
      if (file_exists($ludwig_json)) {
        $packages = file_get_contents($ludwig_json);
        $packages = json_decode($packages, TRUE);
      }
      $version = $packages['require'][$package_name]['version'];
      $require = $this->dirName . '/lib/' . str_replace('/', '-', $package_name) . '/' . $version . '/' . $file_to_require;
      if (!empty($version) && file_exists($require)) {
        require_once $require;
      }
    }
  }

}
