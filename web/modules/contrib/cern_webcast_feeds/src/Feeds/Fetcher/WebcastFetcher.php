<?php

namespace Drupal\cern_webcast_feeds\Feeds\Fetcher;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\feeds\Exception\EmptyFeedException;
use Drupal\feeds\FeedInterface;
use Drupal\feeds\Plugin\Type\ClearableInterface;
use Drupal\feeds\Plugin\Type\Fetcher\FetcherInterface;
use Drupal\feeds\Plugin\Type\PluginBase;
use Drupal\feeds\Result\HttpFetcherResult;
use Drupal\feeds\StateInterface;
use Drupal\feeds\Utility\Feed;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines an HTTP fetcher.
 *
 * @FeedsFetcher(
 *   id = "webcast",
 *   title = @Translation("Webcast Fetcher"),
 *   description = @Translation("Fetches webcast events."),
 *   form = {
 *     "configuration" = "Drupal\cern_webcast_feeds\Feeds\Fetcher\Form\WebcastFetcherForm",
 *     "feed" = "Drupal\cern_webcast_feeds\Feeds\Fetcher\Form\WebcastFetcherFeedForm",
 *   },
 *   arguments = {"@http_client", "@cache.feeds_download", "@file_system"}
 * )
 */
class WebcastFetcher extends PluginBase implements ClearableInterface, FetcherInterface {

  /**
   * The Guzzle client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * The cache backend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Drupal file system helper.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs an UploadFetcher object.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param array $plugin_definition
   *   The plugin definition.
   * @param \GuzzleHttp\ClientInterface $client
   *   The Guzzle client.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The Drupal file system helper.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, ClientInterface $client, CacheBackendInterface $cache, FileSystemInterface $file_system) {
    $this->client = $client;
    $this->cache = $cache;
    $this->fileSystem = $file_system;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function fetch(FeedInterface $feed, StateInterface $state) {
    $sink = $this->fileSystem->tempnam('temporary://', 'feeds_webcast_fetcher');
    $sink = $this->fileSystem->realpath($sink);

    $source_url = $this->makeUrl($feed->getConfigurationFor($this));
    $response = $this->get($source_url, $sink, $this->getCacheKey($feed));
    $feed->setSource($source_url);

    // 304, nothing to see here.
    if ($response->getStatusCode() == Response::HTTP_NOT_MODIFIED) {
      $state->setMessage($this->t('The feed has not been updated.'));
      throw new EmptyFeedException();
    }

    return new HttpFetcherResult($sink, $response->getHeaders());
  }

  /**
   * Helper method to build the URL to call Webcast webservice.
   */
  protected function makeUrl($source_config) {
    $categories = $source_config['categories'];
    $date_from = $source_config['date_from'];
    $date_to = $source_config['date_to'];
    $only_public = $source_config['only_public'];
    $params = array('api' => 'export');
    if (!empty($categories)) {
      $params['indico_cat'] = str_replace(',', '+', $categories);
    }
    if ($date_from) {
      $params['from'] = $date_from;
    }
    if ($date_to) {
      $params['to'] = $date_to;
    }
    if ($only_public) {
      $params['only_public'] = 'true';
    } else {
      $params['only_public'] = 'false';
    }

    $params['timestamp'] = time();
    $params['ak'] = $this->getConfiguration('api_key');
    $secret = $this->getConfiguration('secret_key');
    $url = '/api/v1/export/?';
    uksort($params, 'strcasecmp');
    $url .= UrlHelper::buildQuery($params);
    if ($secret) {
      $signature = hash_hmac('sha1', $url, $secret);
      $url .= '&signature=' . $signature;
    }
    \Drupal::logger('cern_webcast_feeds')->notice(WEBCAST_URL.$url);

    return WEBCAST_URL . $url;
  }

  /**
   * Performs a GET request.
   *
   * @param string $url
   *   The URL to GET.
   * @param string $cache_key
   *   (optional) The cache key to find cached headers. Defaults to false.
   *
   * @return \Guzzle\Http\Message\Response
   *   A Guzzle response.
   *
   * @throws \RuntimeException
   *   Thrown if the GET request failed.
   */
  protected function get($url, $sink, $cache_key = FALSE) {
    $url = Feed::translateSchemes($url);

    $options = [RequestOptions::SINK => $sink];

    // Add cached headers if requested.
    if ($cache_key && ($cache = $this->cache->get($cache_key))) {
      if (isset($cache->data['etag'])) {
        $options[RequestOptions::HEADERS]['If-None-Match'] = $cache->data['etag'];
      }
      if (isset($cache->data['last-modified'])) {
        $options[RequestOptions::HEADERS]['If-Modified-Since'] = $cache->data['last-modified'];
      }
    }

    try {
      $response = $this->client->get($url, $options);
    }
    catch (RequestException $e) {
      $args = ['%site' => $url, '%error' => $e->getMessage()];
      throw new \RuntimeException($this->t('The feed from %site seems to be broken because of error "%error".', $args));
    }

    if ($cache_key) {
      $this->cache->set($cache_key, array_change_key_case($response->getHeaders()));
    }

    return $response;
  }

  /**
   * Returns the download cache key for a given feed.
   *
   * @param \Drupal\feeds\FeedInterface $feed
   *   The feed to find the cache key for.
   *
   * @return string
   *   The cache key for the feed.
   */
  protected function getCacheKey(FeedInterface $feed) {
    return $feed->id() . ':' . hash('sha256', $feed->getSource());
  }

  /**
   * {@inheritdoc}
   */
  public function clear(FeedInterface $feed, StateInterface $state) {
    $this->onFeedDeleteMultiple([$feed]);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'api_key' => '',
      'secret_key' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultFeedConfiguration() {
    return [
      'categories' => '',
      'date_from' => 'today',
      'date_to' => 'today',
      'only_public' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function onFeedDeleteMultiple(array $feeds) {
    foreach ($feeds as $feed) {
      $this->cache->delete($this->getCacheKey($feed));
    }
  }

}
