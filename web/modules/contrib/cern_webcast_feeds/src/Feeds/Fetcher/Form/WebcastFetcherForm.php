<?php

namespace Drupal\cern_webcast_feeds\Feeds\Fetcher\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\feeds\Plugin\Type\ExternalPluginFormBase;
use Drupal\Core\Url;

/**
 * The configuration form for http fetchers.
 */
class WebcastFetcherForm extends ExternalPluginFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form['api_key'] = array(
      '#type' => 'textfield',
      '#title' => t('API Key'),
      '#default_value' => $this->plugin->getConfiguration('api_key'),
      '#size' => 40,
      '#maxlength' => 36,
      '#description' => $this->t('The API key for your Webcast user. See <em><a href="http://webcast.cern.ch">Webcast</a> &raquo; My Profile &raquo; HTTP API</em> for further information.'),
      '#required' => TRUE,
    );
    $form['secret_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Secret Key'),
      '#default_value' => $this->plugin->getConfiguration('secret_key'),
      '#size' => 40,
      '#maxlength' => 36,
      '#description' => t('The secret key for your Webcast user.'),
      '#required' => FALSE,
    );

    return $form;
  }

  /**
   * Validate Webcast parametrisation.
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    /*$uuid_re = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';
    $values = $form_state->getValues();
    if ($values['api_key'] && !preg_match($uuid_re, $values['api_key'])) {
      $form_state->setErrorByName('feeds][api_key', $this->t('The API key has an invalid format.'));
    }
    $secret_key = $values['secret_key'];
    if ($secret_key && !preg_match($uuid_re, $secret_key)) {
      $form_state->setErrorByName('feeds][secret_key', $this->t('The secret key has an invalid format.'));
    }*/
  }

  /**
   * Callback to store the fetcher configuration.
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('To import data from webcast you might now want to <a href="@url">create a new <em>Webcast Feed</em> content node</a>.', array('@url' => URL::fromRoute('entity.feeds_feed.add_form', ['feeds_feed_type' => 'webcast'])->toString())));
    if ($form_state->getValue('secret_key')) {
      drupal_set_message(t('By providing a secret key, protected events may be imported. <strong>You are required to ensure that only authorized users are able to access information about these events!</strong>'), 'warning');
    }
    parent::submitConfigurationForm($form, $form_state);
  }

}
