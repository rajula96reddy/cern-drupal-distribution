<?php

namespace Drupal\Tests\domain_301_redirect\Traits;

/**
 * Common functions used by domain_301_redirect tests.
 */
trait Domain301RedirectFunctionalTestTrait {

  /**
   * Enable the 301 redirect for the provided domain.
   *
   * @param string $domain
   *   The domain to set in domain_301_redirect.settings.
   */
  protected function enableRedirect($domain) {
    $config = $this->configFactory->getEditable('domain_301_redirect.settings');
    $config->merge([
      'enabled' => 1,
      'domain' => $domain,
    ])->save();
    drupal_flush_all_caches();
  }

  /**
   * Disable the domain 301 redirect.
   */
  protected function disableRedirect() {
    $config = $this->configFactory->getEditable('domain_301_redirect.settings');
    $config->merge([
      'enabled' => 0,
    ])->save();
    drupal_flush_all_caches();
  }

  /**
   * Perform a get request that doesn't follow a redirect.
   *
   * @param string $url
   *   The test url.
   *
   * @return array
   *   The status code and headers.
   */
  protected function getRedirect($url) {
    $client = $this->getSession()->getDriver()->getClient()->getClient();
    $response = $client->get($url, [
      // Don't follow the redirect, just return the response.
      'allow_redirects' => FALSE,
    ]);
    $statusCode = $response->getStatusCode();
    $headers = $response->getHeaders();
    return [$statusCode, $headers];
  }

}
