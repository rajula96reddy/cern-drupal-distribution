<?php

namespace Drupal\domain_301_redirect\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\domain_301_redirect\Domain301RedirectManagerInterface;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Controller to check that the domain is working.
 */
class Domain301RedirectController extends ControllerBase {

  /**
   * The domain 301 redirect manager.
   *
   * @var \Drupal\domain_301_redirect\Domain301RedirectManagerInterface
   */
  protected $redirectManager;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * The domain_301_redirect.settings config.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function __construct(Domain301RedirectManagerInterface $redirectManager, RequestStack $request_stack, ConfigFactoryInterface $config_factory) {
    $this->redirectManager = $redirectManager;
    $this->request = $request_stack->getCurrentRequest();
    $this->config = $config_factory->get('domain_301_redirect.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('domain_301_redirect.manager'),
      $container->get('request_stack'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function check() {
    $response = new CacheableJsonResponse([
      'enabled' => $this->config->get('enabled'),
    ]);
    return $response->addCacheableDependency(CacheableMetadata::createFromRenderArray([
      '#cache' => [
        'max-age' => 0,
        'contexts' => ['url.query_args:token'],
        'tags' => ['config:domain_301_redirect.settings'],
      ],
    ]));
  }

  /**
   * Checks access for a specific request.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access(AccountInterface $account) {
    $token = $this->redirectManager->getRedirectCheckToken();
    if ($this->request->query->get('token') == $token) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }

}
