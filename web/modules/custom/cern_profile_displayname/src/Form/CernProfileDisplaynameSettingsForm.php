<?php

namespace Drupal\cern_profile_displayname\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CernProfileDisplaynameSettingsForm.
 *
 * @package Drupal\cern_profile_displayname\Form
 */
class CernProfileDisplaynameSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cern_profile_displayname_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cern_profile_displayname.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('cern_profile_displayname.settings');

    // Displayname configurations.
    $form['Displayname_configurations'] = array('#type' => 'fieldset', '#title' => t('Real name configurations'));

    $form['Displayname_configurations']['show_account_name'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show account name (useful to recognize secondary accounts or services)'),
      '#description' => t('If option is disabled: <em>Firstname Lastname</em> <br>If option is enabled: <em>Firstname Lastname (account_name)</em>'),
      '#default_value' => $config->get('show_account_name'),
    );

    // Redirect to Profiles site configuration.
    $form['redirect'] = array('#type' => 'fieldset', '#title' => t('Configure redirect to CERN Profiles site'));
    $form['redirect']['enable_redirect'] = array(
      '#type' => 'checkbox',
      '#title' => t('Rewrite URLs'),
      '#description' => t('Site\'s users profiles can be redirected to an external central profiles page. Select this option to enable URL rewriting to point to the defined site, currently <a href="https://phonebook.cern.ch">CERN Phonebook</a> personDetails page  instead of the local user profile. <br> NB: The path /user/&#60uid&#62 and all his subdirectories (ie. /user/&#60uid&#62/edit, /user/&#60uid&#62/devel) will be redirected.'),
      '#default_value' => $config->get('enable_redirect'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cern_profile_displayname.settings');
    $values = $form_state->getValues();
    $config->set('show_account_name', $values['show_account_name']);
    $config->set('enable_redirect', $values['enable_redirect']);
    #$config->set('profiles_site', $values['profiles_site']);
    #$config->set('enable_redirect_pages', $values['enable_redirect_pages']);
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    #$value = $form_state->getValue('profiles_site');
    #if (!UrlHelper::isValid($value, TRUE)) {
    #  $form_state->setErrorByName('cern_profile_displayname_profiles_site', t('The redirect path %value is not valid. The path must be absolute.', array('%value' => $value)));
    #}
  }

}
