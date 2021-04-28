<?php

namespace Drupal\cern_integration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * An example controller.
 */
class BackendController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function redirect() {
     return new RedirectResponse("/_site");
  }

}

