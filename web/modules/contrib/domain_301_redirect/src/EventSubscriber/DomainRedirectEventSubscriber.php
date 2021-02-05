<?php

namespace Drupal\domain_301_redirect\EventSubscriber;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\domain_301_redirect\Domain301Redirect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class DomainRedirectEventSubscriber.
 *
 * @package Drupal\domain_301_redirect
 */
class DomainRedirectEventSubscriber implements EventSubscriberInterface {

  /**
   * A config object for the Domain 301 Redirect configuration.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Current user acocunt.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $userAccount;

  /**
   * The path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;

  /**
   * The path alias manager.
   *
   * @var \Drupal\Core\Path\AliasManagerInterface
   */
  protected $pathAliasManager;

  /**
   * Constructs a DomainRedirectEventSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory object.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Session\AccountProxyInterface $user_account
   *   The current user object.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   Path Matcher interface.
   * @param \Drupal\Core\Path\AliasManagerInterface $pathAliasManager
   *   Path alias manager interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RequestStack $request_stack, AccountProxyInterface $user_account, PathMatcherInterface $path_matcher, AliasManagerInterface $pathAliasManager) {
    $this->config = $config_factory->get('domain_301_redirect.settings');
    $this->request = $request_stack->getCurrentRequest();
    $this->userAccount = $user_account;
    $this->pathMatcher = $path_matcher;
    $this->pathAliasManager = $pathAliasManager;
  }

  /**
   * {@inheritdoc}
   *
   * @see RedirectResponseSubscriber::onKernelRequestCheckRedirect
   */
  public static function getSubscribedEvents() {
    // Set the redirect to be before 404 or redirect checks.
    $events[KernelEvents::REQUEST] = ['requestHandler', 34];
    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is dispatched.
   *
   * @todo Needs a service which will handle the exclusion/inclusion of
   * the mentioned path/page.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The response event.
   */
  public function requestHandler(GetResponseEvent $event) {
    // If domain redirection is not enabled, then no need to process further.
    if (!$this->config->get('enabled')) {
      return;
    }

    // If user has 'bypass' permission, then no need to process further.
    if ($this->userAccount->hasPermission('bypass domain 301 redirect')) {
      return;
    }

    // Check that the domain is set just to be safe.
    $domain = trim(trim($this->config->get('domain')), '/');
    if (empty($domain)) {
      return;
    }

    // Check the path configuration to see if we should bypass redirection.
    if ($this->checkPath()) {
      return;
    }

    // If domain doesn't contain http/https, then add those to domain.
    if (!preg_match('|^https?://|', $domain)) {
      $domain = 'http://' . $domain;
    }

    // Parse the domain to get various settings like port.
    $domain_parts = parse_url($domain);
    $parsed_domain = $domain_parts['host'];
    $parsed_domain .= !empty($domain_parts['port']) ? ':' . $domain_parts['port'] : '';
    $parsed_scheme = $domain_parts['scheme'];

    // If we're not on the same host, the user has access and this page isn't
    // an exception, redirect.
    $scheme = $this->request->getScheme();
    $host = $this->request->getHttpHost();
    if ($parsed_domain != $host || $parsed_scheme != $scheme) {
      $uri = $this->request->getRequestUri();
      $response = new TrustedRedirectResponse($domain . $uri, 301);
      // Add the same header used by the redirect module, often used in Varnish.
      $response->headers->add([
        'X-Redirect-ID' => 0,
      ]);
      // Add cache metadata to cache the response.
      $response->addCacheableDependency(CacheableMetadata::createFromRenderArray([
        '#cache' => [
          'max-age' => Cache::PERMANENT,
          'contexts' => [
            'url',
            'user.permissions',
          ],
          'tags' => [
            'config:domain_301_redirect.settings',
          ],
        ],
      ]));
      $event->setResponse($response);
    }
  }

  /**
   * Checks if the current path should be protected or not.
   *
   * @return bool
   *   Whether to bypass the redirection or not.
   */
  private function checkPath() {
    // Get current path but always get the alias.
    $current_path = $this->request->getRequestUri();
    $path = $this->pathAliasManager->getAliasByPath($current_path);

    $path_match = FALSE;
    $bypass = FALSE;

    if ($this->pathMatcher->matchPath($path, $this->config->get('pages'))) {
      $path_match = TRUE;
    }

    switch ($this->config->get('applicability')) {
      case Domain301Redirect::EXCLUDE_METHOD:
        if ($path_match) {
          $bypass = TRUE;
        }
        break;

      case Domain301Redirect::INCLUDE_METHOD:
        if (!$path_match) {
          $bypass = TRUE;
        }
        break;
    }

    return $bypass;
  }

}
