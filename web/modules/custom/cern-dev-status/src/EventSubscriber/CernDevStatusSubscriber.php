<?php

namespace Drupal\cern_dev_status\EventSubscriber;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger;

/**
 * Perform IP Restriction validation on each request.
 */
class CernDevStatusSubscriber implements EventSubscriberInterface {
  use StringTranslationTrait;

  /**
   * Request check callback.
   */
  public function checkCernDevStatus(FilterResponseEvent $event) {
    $config = \Drupal::config('cern_dev_status.settings');
    // IP RESTRICTION.
    $en_restrictip = _cern_dev_status_get_default_value($config->get('enable_restrict_ip'), DEFAULT_ENABLE_RESTRICTIP);
    if ($en_restrictip) {
      $access_denied = FALSE;

      $current_path = \Drupal::request()->getRequestUri();
      // If override option enabled: block access only for anonymous users and.
      // Let authenticated users in even if their IP is not on the list!
      // If not enabled go ahead and check the ip restriction!
      $user = \Drupal::currentUser();
      $override_auth_usrs = _cern_dev_status_get_default_value($config->get('restrict_ip_override_auth_users'), DEFAULT_ENABLE_OVERRIDE_AUTH_USERS);

      if ((!$override_auth_usrs) || (($override_auth_usrs) && ($user->isAnonymous()))) {
        //Check if the user is trying to login (shouldn't be restricted)
        $patterns = ['/user/login', '/user/password', '/user/logout', '/user/reset/', '/user/register', '/saml_login'];
        $patterns = array_map(function($value) {
          return preg_quote($value, '/');
        }, $patterns);
        $patterns = '/'.implode('|', $patterns).'/';
        if(preg_match($patterns, $current_path) === 1){
          return;
        }
        $ip_addresses = trim(_cern_dev_status_get_default_value($config->get('restrict_ip_address_list'), DEFAULT_IP_ADDDRESS_LIST));
        if (strlen($ip_addresses)) {
          $ip_addresses = explode(PHP_EOL, $ip_addresses);
          $users_ip = \Drupal::request()->getClientIp();
          $users_ipv6 = FALSE;

          if (filter_var($users_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $users_ipv6 = TRUE;
          }
          $access_denied = TRUE;
          foreach ($ip_addresses as $ip_address) {
            $ip_address = trim ($ip_address);
            // IPv6 support  XXX it is really greedy and needs to be improved.
            if ($users_ipv6) {
              $my_ip_address = $ip_address;
              if (filter_var($my_ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // Handle IPv6.
                $bin_users_ip         = inet_pton($users_ip);
                $bin_my_ip_address    = inet_pton($my_ip_address);

                if ($bin_users_ip && $bin_my_ip_address) {
                  // We have real IPv6.
                  $tab_bin_users_ip      = str_split($bin_users_ip);
                  $tab_bin_my_ip_address = str_split($bin_my_ip_address);

                  $txt_bin_users_ip      = '';
                  $txt_bin_my_ip_address = '';
                  foreach ($tab_bin_users_ip as $x) {
                    $txt_bin_users_ip .= bin2hex($x);
                  }
                  foreach ($tab_bin_my_ip_address as $y) {
                    $txt_bin_my_ip_address .= bin2hex($y);
                  }
                  // Remove trailing zeros.
                  $txt_bin_my_ip_address = rtrim($txt_bin_my_ip_address, "0");

                  $common = similar_text($txt_bin_my_ip_address, $txt_bin_users_ip);
                  if ((int) $common === (int) strlen($txt_bin_my_ip_address)) {
                    $access_denied = FALSE;
                    break;
                  }
                }
              }
              else {
                continue;
              }
            }
            // IPv4
            // ip range in CIDR syntax.
            elseif (preg_match('@\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\/(?:[12]?[0-9]|3[0-2])\b@', $ip_address)) {
              if (IpUtils::checkIp($users_ip, $ip_address)) {
                $access_denied = FALSE;
                break;
              }
            }

            // Single IP.
            elseif ($ip_address == $users_ip) {
              $access_denied = FALSE;
              break;
            }
          }
        }
      }

      if ($current_path != "/admin/modules/uninstall/confirm") {
        $cern_access_denied_url = Url::fromRoute('cern_dev_status.access_denied')->toString();
        if ($access_denied) {
          if (strpos($current_path, $cern_access_denied_url) === FALSE) {
            $event->setResponse(new RedirectResponse($cern_access_denied_url));
          }

          $error_text = $this->t("This site cannot be accessed from your IP address.");
          $authenticated_bypass = _cern_dev_status_get_default_value($config->get('restrict_ip_override_auth_users'), DEFAULT_ENABLE_OVERRIDE_AUTH_USERS);
          $authenticate_text = ($authenticated_bypass) ? $this->t("If you have a valid CERN account, please 'Sign' to access site contents.") : '';
          $site_email = \Drupal::config('system.site')->get('mail');
          $contact_mail = trim(_cern_dev_status_get_default_value($config->get('restrict_ip_mail_address'), $site_email));
          if (strlen($contact_mail)) {
            $contact_mail = str_replace('@', '[at]', $contact_mail);
          }
          $contact_text = (strlen($contact_mail)) ? $this->t('If you feel this is in error, please contact an administrator at <span id="cern_dev_status_contact_mail">@email</span>.', array('@email' => $contact_mail)) : FALSE;
          \Drupal::messenger()->addMessage($error_text, 'error');
          \Drupal::messenger()->addMessage($authenticate_text, 'error');
          \Drupal::messenger()->addMessage($contact_text, 'error');
          _cern_dev_status_restrict_ip_value(TRUE);
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('checkCernDevStatus', 30);
    return $events;
  }

}
