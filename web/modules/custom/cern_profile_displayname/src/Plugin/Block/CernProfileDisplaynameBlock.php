<?php

namespace Drupal\cern_profile_displayname\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * Provides a 'CernProfileDisplaynameBlock' block.
 *
 * @Block(
 *  id = "cern_profile_displayname_block",
 *  admin_label = @Translation("CERN Display Real Name - Signed in as"),
 * )
 */
class CernProfileDisplaynameBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['cern_profile_displayname_block']['#markup'] = $this->getBlockContent();

    //Not cache the results since each user will have it's own link
    #$build['#cache']['max-age'] = 0;
    $build['#cache']['contexts'] = array('user');

    return $build;
  }

  /**
   * Gets the block content
   */
  protected function getBlockContent() {
    $user = \Drupal::currentUser();
    if ($user->isAnonymous()) {
      $sign_in_link = Link::createFromRoute(t('Sign in'), 'simplesamlphp_auth.saml_login');
      $output = '<div id="cern_signedinas">' . $sign_in_link->toString() . '</div>';
    }
    else {
      $config = \Drupal::config('cern_profile_displayname.settings');
      $enred = $config->get('enable_redirect');
      $sign_out_link = Link::createFromRoute(t('Sign out'), 'user.logout');
      if ($enred) {
        $username = $this->linkToProfilesSite($user);
      }
      else {
        $username = $user->getDisplayName();
      }
      $signedin_as = t('Signed in as @username', ['@username' => $username]);
      $output = '<div id="cern_signedinas">' . $signedin_as . ' | ' . $sign_out_link->toString() . '</div>';
    }

    return $output;
  }

  /**
   * Helper function to let other modules to print the name and link to the profiles site,
   * given an account name ".
   * Returns the email address for external accounts.
   */
  protected function linkToProfilesSite(AccountInterface $account) {
    $account = User::load($account->id());
    if (isset($account->field_person_id_ccid->value)) {
      if ($account->field_person_id_ccid->value != '-1') {
        $config = \Drupal::config('cern_profile_displayname.settings');
        $path_profiles_site = $config->get('profiles_site');
        $path_profiles_site = _cern_profile_displayname_get_profiles_site_url($path_profiles_site);
        // Return the cern real name linking to the profile site.
        return Link::fromTextAndUrl($account->getDisplayName(), URL::fromUri($path_profiles_site . "?id=" . $account->field_person_id_ccid->value))->toString();
      }
      // For external accounts returns the email address.
      else {
        return $account->getEmail();
      }
    }
    else {
      return $account->getDisplayName();
    }
  }
}
