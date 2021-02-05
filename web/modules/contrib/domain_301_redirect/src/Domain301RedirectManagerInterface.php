<?php

namespace Drupal\domain_301_redirect;

/**
 * Interface for Domain301RedirectManager.
 */
interface Domain301RedirectManagerInterface {

  /**
   * Checks if a domain actually points to this site.
   *
   * @param string $domain
   *   The domain to be checked.
   *
   * @return bool
   *   Returns TRUE if the domain passes the check. FALSE otherwise.
   */
  public function checkDomain($domain);

  /**
   * Determine if connection should be refreshed.
   *
   * @param string $domain
   *   The domain to be checked.
   *
   * @return array
   *   Returns the list of options that domain_301_redirect provides.
   */
  public function checkRedirectLoop($domain);

  /**
   * Generate a url to the redirect check controller endpoint.
   *
   * @return \Drupal\Core\Url
   *   The redirect check url.
   */
  public function getRedirectCheckUrl($domain);

  /**
   * Generate a token based on redirect_last_checked.
   *
   * @return string
   *   Returns the token.
   */
  public function getRedirectCheckToken();

}
