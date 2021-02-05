<?php

namespace Drupal\Tests\domain_301_redirect\Functional;

use Drupal;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\Traits\Core\CronRunTrait;
use Drupal\Tests\domain_301_redirect\Traits\Domain301RedirectFunctionalTestTrait;

/**
 * Test the Domain301RedirectManager service.
 *
 * @group domain_301_redirect
 * @covers \Drupal\domain_301_redirect\Domain301RedirectManager
 */
class RedirectManagerTest extends BrowserTestBase {

  use CronRunTrait;
  use Domain301RedirectFunctionalTestTrait;

  protected static $modules = ['domain_301_redirect'];

  protected $domain;
  protected $notTheDomain;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The domain 301 redirect manager.
   *
   * @var \Drupal\domain_301_redirect\Domain301RedirectManager
   */
  protected $redirectManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $test_url = getenv('SIMPLETEST_BASE_URL');
    $parsed_url = parse_url($test_url);
    if (!isset($parsed_url['host'])) {
      $this->markTestSkipped('Missing SIMPLETEST_BASE_URL');
      return;
    }
    $host = trim($parsed_url['host'], '/');
    $this->domain = "http://{$host}/";
    $this->notTheDomain = "http://not.{$host}/";

    parent::setUp();

    $this->configFactory = Drupal::service('config.factory');
    $this->redirectManager = Drupal::service('domain_301_redirect.manager');
  }

  /**
   * Check that the the redirect domain resolves to this site.
   */
  public function testCheckDomain() {

    $isDomainOk = $this->redirectManager->checkDomain($this->domain);
    $this->assertTrue($isDomainOk, 'The domain resolves.');

    $this->enableRedirect($this->notTheDomain);
    $isDomainOk = $this->redirectManager->checkDomain($this->notTheDomain);
    list($statusCode,) = $this->getRedirect($this->domain);
    $this->assertEquals(301, $statusCode, 'The site is redirecting.');
    $this->assertFalse($isDomainOk, 'The redirect domain does not resolve.');

    // Run cron which should disable the 301 redirect.
    Drupal::service('cron')->run();
    $this->configFactory->clearStaticCache();

    $config = $this->configFactory->get('domain_301_redirect.settings');
    $enabled = $config->get('enabled');
    $this->assertEquals(0, $enabled, 'The 301 redirect is no longer enabled.');

    // Request the site and see that it doesn't redirect.
    $this->drupalGet('<front>');
    $statusCode = $this->getSession()->getStatusCode();
    $this->assertEquals(200, $statusCode, 'The site is not redirecting.');

  }

  // TODO A test would be great, if possible.
  // public function testRedirectLoop() {
  //  $this->markTestIncomplete('Not yet implemented');
  // }
}
