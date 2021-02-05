<?php

namespace Drupal\Tests\domain_301_redirect\Functional;

use Drupal;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\domain_301_redirect\Traits\Domain301RedirectFunctionalTestTrait;

/**
 * Test the redirection of a domain using the DomainRedirectEventSubscriber.
 *
 * @group domain_301_redirect
 * @covers Drupal\domain_301_redirect\EventSubscriber\DomainRedirectEventSubscriber
 */
class Domain301RedirectTest extends BrowserTestBase {

  use Domain301RedirectFunctionalTestTrait;

  protected static $modules = ['domain_301_redirect'];

  protected $domain;

  protected $redirectDomain;

  protected $secureDomain;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

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
    $this->redirectDomain = "http://redirect.{$host}/";
    $this->secureDomain = "https://{$host}/";

    parent::setUp();

    $this->configFactory = Drupal::service('config.factory');
  }

  /**
   * Test redirecting to various domains.
   */
  public function testDomainRedirect() {
    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $this->assertNotEquals(301, $statusCode, 'No redirect is performed.');

    // Set a redirect domain.
    $this->enableRedirect($this->redirectDomain);

    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $this->assertEquals(301, $statusCode, 'Domain redirect is performed.');
    $location = $headers['Location'][0] ?? NULL;
    $this->assertEquals($this->redirectDomain, $location, 'Domain redirect url is correct.');

    // A 404 request should still redirect.
    list($statusCode, $headers) = $this->getRedirect($this->domain . 'not-found');
    $this->assertEquals(301, $statusCode, 'Domain redirect is performed for 404 urls.');
    $location = $headers['Location'][0] ?? NULL;
    $this->assertEquals($this->redirectDomain . 'not-found', $location, 'Domain redirect url is correct for 404 urls.');

    // A 403 request should still redirect.
    list($statusCode, $headers) = $this->getRedirect($this->domain . 'admin/config');
    $this->assertEquals(301, $statusCode, 'Domain redirect is performed for 403 urls.');
    $location = $headers['Location'][0] ?? NULL;
    $this->assertEquals($this->redirectDomain . 'admin/config', $location, 'Domain redirect url is correct for 403 urls.');

    // An http request should redirect when we're redirecting to https.
    $this->enableRedirect($this->secureDomain);
    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $this->assertEquals(301, $statusCode, 'Domain redirect is performed on an https domain.');
    $location = $headers['Location'][0] ?? NULL;
    $this->assertEquals($this->secureDomain, $location, 'Domain redirect url is correct on an https domain.');

    $this->disableRedirect();
    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $this->assertNotEquals(301, $statusCode, 'The site is no longer redirecting.');
  }

  /**
   * Test the cacheability of the redirect response.
   */
  public function testRedirectCacheability() {
    $this->enableRedirect($this->redirectDomain);

    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $xDrupalCache = $headers['X-Drupal-Cache'][0] ?? NULL;
    if (!isset($xDrupalCache)) {
      $this->markTestSkipped('Missing X-Drupal-Cache header');
    }

    $this->assertEquals(301, $statusCode, 'The domain is redirecting.');
    $this->assertEquals('MISS', $xDrupalCache, 'The first redirect is a cache miss.');

    list($statusCode, $headers) = $this->getRedirect($this->domain);
    $xDrupalCache = $headers['X-Drupal-Cache'][0] ?? NULL;
    $this->assertEquals(301, $statusCode);
    $this->assertEquals('HIT', $xDrupalCache, 'The second redirect is a cache hit.');
  }

  /**
   * Test the user permission to bypass the domain 301 redirect.
   *
   * @todo
   */
  public function testRedirectBypass() {
    $this->markTestIncomplete('Not yet implemented');
  }

}
