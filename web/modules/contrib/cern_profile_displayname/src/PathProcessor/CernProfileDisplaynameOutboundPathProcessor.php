<?php

namespace Drupal\cern_profile_displayname\PathProcessor;

use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class CernProfileDisplaynameOutboundPathProcessor implements OutboundPathProcessorInterface {

  /**
   *
   */
  public function processOutbound($path, &$options = array(), Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    $config = \Drupal::config('cern_profile_displayname.settings');
    $enable_redirect = $config->get('enable_redirect');
    $base_url = $config->get('profiles_site');
    if ($enable_redirect) {
      if (preg_match('@user[/\+%]+([0-9]+)(.*)?@', $path, $matches)) {
        $opt_enable_redirect_pages = [
          'edit' => $config->get('enable_redirect_pages.edit'),
          'devel' => $config->get('enable_redirect_pages.devel'),
          'contact' => $config->get('enable_redirect_pages.contact'),
        ];

        if (!$opt_enable_redirect_pages['edit'] && $matches[2] == '/edit'
          || !$opt_enable_redirect_pages['devel'] && $matches[2] == '/devel'
          || !$opt_enable_redirect_pages['contact'] && $matches[2] == '/contact') {
          return $path;
        }
        $account = User::load($matches[1]);
        $new_options = $this->getUserProfileLinkOptions($account, $base_url);
      }
      elseif ($path == '/user') {
        $account = User::load(\Drupal::currentUser()->id());
        $new_options = $this->getUserProfileLinkOptions($account, $base_url);
      }
      if (isset($new_options)) {
        if (in_array($request->getPathInfo(), ['/user', '/user/login'])){
          // Save the session so things like messages get saved.
          $request->getSession()->save();
          $response = new TrustedRedirectResponse($new_options['base_url'].'?'.http_build_query($new_options['query']));
          $response->prepare($request);
          // Make sure to trigger kernel events.
          \Drupal::service('kernel')->terminate($request, $response);
          $response->send();
        }
        $options = array_merge($options, $new_options);
        $path = '';
      }
    }

    return $path;
  }

  /**
   * Added 21/05/2015 eduardoa
   * Helper function to create the link to the user profile
   * Now either anonymous and authenticated users can go to social (07/09/2015)
   * For authenticated users will point to social.cern.ch profile.
   */
  protected function getUserProfileLinkOptions(User $account, $base_url) {
    $options = [];
    $ccid = "";
    if (isset($account->field_person_id_ccid->value)) {
      if ($account->field_person_id_ccid->value != '-1') {
        $ccid = $account->field_person_id_ccid->value;
      }
    }

    $options['query'] = ['id' => $ccid];
    $options['base_url'] = $base_url;

    return $options;
  }

}
