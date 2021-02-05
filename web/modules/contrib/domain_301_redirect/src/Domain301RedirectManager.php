<?php

namespace Drupal\domain_301_redirect;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\PrivateKey;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines an Domain301RedirectManager service.
 */
class Domain301RedirectManager implements Domain301RedirectManagerInterface {

  /**
   * A config object for the Domain 301 Redirect configuration.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The Drupal site private key.
   *
   * @var \Drupal\Core\PrivateKey
   */
  protected $privateKey;

  /**
   * The http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Constructs an Domain301RedirectManager object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Configuration Factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\PrivateKey $private_key
   *   The site private key.
   * @param \GuzzleHttp\ClientInterface $client
   *   The http client.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RequestStack $request_stack, PrivateKey $private_key, ClientInterface $client) {
    $this->config = $config_factory->get('domain_301_redirect.settings');
    $this->request = $request_stack->getCurrentRequest();
    $this->privateKey = $private_key;
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function checkDomain($domain) {
    if (!empty($domain)) {
      $retries = $this->config->get('domain_check_retries');

      // Try to contact the redirect domain, if this fails, retry N times after
      // a pause.
      $uri = $this->getRedirectCheckUrl($domain)->toString();
      for ($i = 1; $i <= $retries; $i++) {
        try {
          $response = $this->client->get($uri);
          if ($response->getStatusCode() == 200) {
            return TRUE;
          }
        }
        catch (RequestException $e) {
        }

        // Pause between retries.
        if ($i < $retries) {
          sleep(10);
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkRedirectLoop($domain, &$redirect_count = 0) {
    // Get host from configured domain.
    $host = $this->request->getHost();
    $scheme = $this->request->getScheme();
    $parsed_url = parse_url($domain);
    $redirect_host = mb_strtolower($parsed_url['host']);
    $redirect_scheme = $parsed_url['scheme'] ?? 'http';

    // Redirecting back to this site actually is actively ignored in hook_init,
    // so it makes no sense to allow users to set this as a value. On the other
    // hand when the admin is on the redirected domain he should still be able
    // to alter other settings without first disabling redirection. So let's
    // just accept the current host.
    if ($redirect_host == $host && $scheme == $redirect_scheme) {
      return FALSE;
    }

    $redirect_loop = FALSE;
    $loop_max_redirects = $this->config->get('loop_max_redirects');

    $client = $this->client;
    try {
      $response = $client->request('HEAD', $domain, [
        'allow_redirects' => [
          'track_redirects' => TRUE,
          'max' => $loop_max_redirects,
        ],
      ]);
    }
    catch (GuzzleException $e) {
      return FALSE;
    }

    // Check the redirect history for this domain. If it is found then
    // a redirect loop is present.
    $redirect_history = $response->getHeaderLine('X-Guzzle-Redirect-History');
    $redirects_urls = $redirect_history ? explode(', ', $redirect_history) : [];
    // Set the number of redirects as any redirects are probably undesirable.
    $redirect_count = count($redirects_urls);

    foreach ($redirects_urls as $redirect_url) {
      $parsed_url = parse_url($redirect_url);
      $redirect_url_host = mb_strtolower($parsed_url['host']);
      $redirect_url_scheme = $parsed_url['scheme'] ?? 'http';
      if ($redirect_url_host == $host && $redirect_url_scheme == $scheme) {
        $redirect_loop = TRUE;
        break;
      }
    }

    return $redirect_loop;
  }

  /**
   * {@inheritdoc}
   */
  public function getRedirectCheckUrl($domain) {
    $url = Url::fromRoute('domain_301_redirect.check', [], [
      'query' => [
        'token' => $this->getRedirectCheckToken(),
      ],
      'base_url' => $domain,
      'absolute' => TRUE,
    ]);
    return $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getRedirectCheckToken() {
    $redirect_last_checked = $this->config->get('redirect_last_checked') ?? 0;
    $token = Crypt::hmacBase64('domain_301_redirect_check_domain', $redirect_last_checked . Settings::getHashSalt() . $this->privateKey->get());
    return $token;
  }

}
