<?php

namespace Drupal\cern_indico_feeds\Feeds\Fetcher\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\feeds\FeedInterface;
use Drupal\feeds\Plugin\Type\ExternalPluginFormBase;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form on the feed edit page for the HttpFetcher.
 */
class IndicoFetcherFeedForm extends ExternalPluginFormBase implements ContainerInjectionInterface {

  /**
   * The Guzzle client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Constructs an HttpFeedForm object.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The HTTP client.
   */
  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('http_client'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state, FeedInterface $feed = NULL) {
    $form['categories'] = array(
      '#type' => 'textfield',
      '#title' => t('Categories'),
      '#default_value' => $feed->getConfigurationFor($this->plugin)['categories'],
      '#description' => $this->t('Category ID(s) to retrieve events from. Separate multiple IDs with commas. To find out the ID of a category, go to the category in <a href="http://indico.cern.ch">Indico</a> and copy the numeric value next to <em>/category/</em> from the URL.'),
      '#required' => FALSE,
      '#maxlength' => 1024,
    );

    $form['date_from'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Start date'),
      '#default_value' => $feed->getConfigurationFor($this->plugin)['date_from'],
      '#description' => $this->t("The earliest event date in YYYY-MM-DD form. You can also use '+-Xd' to specify an offset based on the current date (e.g. '-2d' for events since 2 days ago). 'today', 'yesterday' and 'tomorrow' are also accepted."),
      '#required' => FALSE,
    );

    $form['date_to'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('End date'),
      '#default_value' => $feed->getConfigurationFor($this->plugin)['date_to'],
      '#description' => $this->t("The latest event date in YYYY-MM-DD form. You can also use '+Xd' to specify an offset based on the start date. 'today' and 'tomorrow' are also accepted."),
      '#required' => FALSE,
    );

    $form['room'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Room'),
      '#default_value' => $feed->getConfigurationFor($this->plugin)['room'],
      '#description' => $this->t("The room where the event must take place. You may use <strong>*</strong> as a wildcard."),
      '#required' => FALSE,
    );

    $form['only_public'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Retrieve only public events'),
      '#default_value' => $feed->getConfigurationFor($this->plugin)['only_public'],
      '#description' => $this->t("Only events visible to anonymous users are retrieved."),
      '#required' => FALSE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state, FeedInterface $feed = NULL) {

    $values = $form_state->getValues();
    $categories = $values['categories'];
    if (preg_match_all('/\w+/', $categories, $matches)) {
      $values['categories'] = implode(',', $matches[0]);
    }
    else {
      $values['categories'] = '';
    }

    if (!$this->validateDate($values['date_from'], FALSE)) {
      $form_state->setError($form['date_from'], $this->t('The start date has an invalid format.'));
    }
    if (!$this->validateDate($values['date_to'], TRUE)) {
      $form_state->setError($form['date_to'], $this->t('The end date has an invalid format.'));
    }

    if (!$values['categories'] && !$values['date_from'] && !$values['date_to'] && !$values['room']) {
      $form_state->setErrorByName('feeds', $this->t('You need to specify some filter criteria.'));
    }

    $form_state->setValues($values);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state, FeedInterface $feed = NULL) {
    $feed->setConfigurationFor($this->plugin, [
      'categories' => $form_state->getValue('categories'),
      'date_from' => $form_state->getValue('date_from'),
      'date_to' => $form_state->getValue('date_to'),
      'room' => $form_state->getValue('room'),
      'only_public' => $form_state->getValue('only_public'),
    ]
    );
  }

  /**
   * Helper method to validate the date interval.
   */
  private function validateDate(&$date, $isEndDate) {
    $date = trim($date);
    if (!$date) {
      return TRUE;
    }
    elseif ($date == 'today' || $date == 'tomorrow') {
      return TRUE;
    }
    elseif (!$isEndDate && $date == 'yesterday') {
      return TRUE;
    }
    elseif (preg_match('/^([+-])?\s*(\d{1,3})\s*(?:d(?:ays)?)?$/', $date, $m)) {
      if (!$m[1]) {
        $m[1] = '+';
      }
      if ($m[1] == '-' && $isEndDate) {
        // End date cannot be specified as today - x days.
        return FALSE;
      }
      if ((int) $m[2]) {
        $date = $m[1] . $m[2] . 'd';
        return TRUE;
      }
    }
    elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date)) {
      return TRUE;
    }
    return FALSE;
  }

}
