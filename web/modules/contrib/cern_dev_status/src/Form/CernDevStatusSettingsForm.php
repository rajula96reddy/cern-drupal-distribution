<?php

namespace Drupal\cern_dev_status\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Class CernDevStatusSettingsForm.
 *
 * @package Drupal\cern_dev_status\Form
 */
class CernDevStatusSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cern_dev_status_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cern_dev_status.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('cern_dev_status.settings');
    $form['cern_dev_status_description'] = array(
      '#markup' => '<h2>' . $this->t('Select the options to enable') . '</h2><p><strong>' . $this->t("This options should be enabled on development sites only!") . '</strong></p>',
    );

    $form['cern_dev_status_enable_noindex'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Avoid indexing'),
      '#description' => $this->t('Add &lt;meta name&#61;&quot;robots&quot; content&#61;&quot;noindex&quot; &#47;&gt; to the head of all pages avoinding automated Internet bots indexing.'),
      '#default_value' => _cern_dev_status_get_default_value($config->get('enable_noindex'), DEFAULT_ENABLE_NOINDEX),
    );

    $form['cern_dev_status_enable_devstyling'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Apply dev-status styling to website'),
      '#description' => $this->t('Add a class to the &#36;classes array that can be used to apply styles via the cern_default theme.<br />These styles should be used on development and pre-production sites only.'),
      '#default_value' => _cern_dev_status_get_default_value($config->get('enable_devstyling'), DEFAULT_ENABLE_DEVSTYLING),
      '#states' => array(
        'visible' => array(
          ':input[name="cern_dev_status_devstyling_type"]' => array('value' => 'dev'),
        ),
      ),
    );

    $form['cern_dev_status_devstyling_type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Applied style to site:'),
      '#disabled' => TRUE,
      '#default_value' => _cern_dev_status_get_default_value($config->get('devstyling_type'), DEFAULT_DEVSTYLING_TYPE),
      '#options' => array(
        'dev' => t('Development'),
        'preprod' => t('Pre-production'),
        'personal' => t('Personal'),
        'archive' => t('Archived'),
      ),
      '#states' => array(
        'visible' => array(
          ':input[name="cern_dev_status_enable_devstyling"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['cern_dev_status_enable_restrict_ip'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable IP restrictions'),
      '#description' => $this->t('Restrict access for anonymous users to specified IP addresses (<a target="_blank" href="https://network.cern.ch/sc/fcgi/sc.fcgi?Action=GetFile&file=ip_networks.html">CERN IP ranges</a> are set as default).'),
      '#default_value' => _cern_dev_status_get_default_value($config->get('enable_restrict_ip'), DEFAULT_ENABLE_RESTRICTIP),
      '#states' => array(
        'disabled' => array(
          ':input[name="cern_dev_status_devstyling_type"]' => array('value' => 'archive'),
        ),
      ),
    );

    $form['cern_dev_status_restrict_ip'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Enter the list of allowed IP addresses below'),
      '#states' => array(
          // Only make visible this fieldset when
          // the restrict_ip option is enabled.
        'visible' => array(
          ':input[name="cern_dev_status_enable_restrict_ip"]' => array('checked' => TRUE),
        ),
        'disabled' => array(
          ':input[name="cern_dev_status_devstyling_type"]' => array('value' => 'archive'),
        ),
      ),
    );

    $form['cern_dev_status_restrict_ip']['restrict_ip_address_description'] = array(
      '#markup' => '<p><strong>' . $this->t('Your current IP address is: %ip_address', array('%ip_address' => \Drupal::request()->getClientIp())) . '</strong></p>',
    );

    $form['cern_dev_status_restrict_ip']['cern_dev_status_restrict_ip_address_list'] = array(
      '#title' => $this->t('Allowed IP Address List'),
      '#description' => $this->t('Enter the list of IP Addresses that are allowed to access the site. If this field is left empty, all IP addresses will be able to access the site. Enter one IP address per line. You may also enter a range of IP addresses in the CIDR format. ex. 137.138.0.0/16, FD01:1458::'),
      '#type' => 'textarea',
    // Add the admin's ip address with the default list.
      '#default_value' => _cern_dev_status_get_default_value($config->get('restrict_ip_address_list'), \Drupal::request()->getClientIp() . PHP_EOL . DEFAULT_IP_ADDDRESS_LIST),
    );

    $form['cern_dev_status_restrict_ip']['cern_dev_status_restrict_ip_override_auth_users'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Override IP restrictions for authenticated users'),
      '#description' => $this->t('Allow authenticated users to login even if their IP address is not included in the Allowed IP Address List above.'),
      '#default_value' => _cern_dev_status_get_default_value($config->get('restrict_ip_override_auth_users'), DEFAULT_ENABLE_OVERRIDE_AUTH_USERS),
    );

    $site_email = $this->config('system.site')->get('mail');
    $form['cern_dev_status_restrict_ip']['cern_dev_status_restrict_ip_mail_address'] = array(
      '#title' => $this->t('Email Address'),
      '#type' => 'textfield',
      '#description' => $this->t('If you would like to include a contact email address in the error message that is shown to users that do not have an allowed IP address, enter the email address here.'),
      '#default_value' => _cern_dev_status_get_default_value(trim($config->get('restrict_ip_mail_address')), $site_email),
    );

    if (count($config->getRawData()) > 1 && $config->get('devstyling_type') !== 'archive') {
      $form['actions']['reset'] = [
        '#type' => 'submit',
        '#value' => $this->t('Reset to defaults'),
        '#submit' => ['::resetFormSubmit'],
        '#weight' => 100,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements submit callback for Reset button.
   *
   * @param array $form
   *   Form render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of the form.
   */
  public function resetFormSubmit(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cern_dev_status.settings');
    $config->clear('enable_noindex');
    $config->clear('enable_devstyling');
    $config->clear('enable_restrict_ip');
    $config->clear('restrict_ip_address_list');
    $config->clear('restrict_ip_override_auth_users');
    $config->clear('restrict_ip_mail_address');
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cern_dev_status.settings');
    $values = $form_state->getValues();
    $config->set('enable_noindex', $values['cern_dev_status_enable_noindex']);
    $config->set('enable_devstyling', $values['cern_dev_status_enable_devstyling']);
    $config->set('devstyling_type', $values['cern_dev_status_devstyling_type']);
    $config->set('enable_restrict_ip', $values['cern_dev_status_enable_restrict_ip']);
    $config->set('restrict_ip_address_list', $values['cern_dev_status_restrict_ip_address_list']);
    $config->set('restrict_ip_override_auth_users', $values['cern_dev_status_restrict_ip_override_auth_users']);
    $config->set('restrict_ip_mail_address', $values['cern_dev_status_restrict_ip_mail_address']);
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $lockedout = TRUE;
    $ip_addresses = $form_state->getValue('cern_dev_status_restrict_ip_address_list');
    if (strlen(trim($ip_addresses))) {
      $ip_addresses = explode(PHP_EOL, trim($form_state->getValue('cern_dev_status_restrict_ip_address_list')));
      foreach ($ip_addresses as $ip_address) {
        $ip_address = trim($ip_address);
        if ($ip_address != '::1') {
          // IPv6 support.
          if (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            if (IpUtils::checkIp6($ip_address, $ip_address)) {
              $lockedout = FALSE;
            }
            continue;
          }
          // One ip.
          elseif (!preg_match('~^\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$~', $ip_address)) {
            // Ip range.
            if (!preg_match('@\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\/(?:[12]?[0-9]|3[0-2])\b@', $ip_address)) {
              $form_state->setErrorByName('cern_dev_status_restrict_ip_address_list', $this->t('@ip_address is not a valid range of IP addresses.', array('@ip_address' => $ip_address)));
            }
            else {
              // $ip_address is an IP range,
              // check if the admin's ip is included in it.
              if (IpUtils::checkIp(\Drupal::request()->getClientIp(), $ip_address)) {
                $lockedout = FALSE;
              }
            }
          }
          else {
            // $ip_address is a single ip, check if it's the admin's ip.
            if ($ip_address == \Drupal::request()->getClientIp()) {
              $lockedout = FALSE;
            }
          }
        }
        else {
          // $ip_address is ::1, check if it's the admin's ip.
          if ($ip_address == \Drupal::request()->getClientIp()) {
            $lockedout = FALSE;
          }
        }
      }
    }
    if ($lockedout) {
      $form_state->setErrorByName('cern_dev_status_restrict_ip_address_list', $this->t('Your IP address is not included in the list, cannot save the settings: you would be locked out of the site.'));
    }

    $emaddr = $form_state->getValue('cern_dev_status_restrict_ip_mail_address');
    if (!\Drupal::service('email.validator')->isValid($emaddr)) {
      $form_state->setErrorByName('cern_dev_status_restrict_ip_mail_address', $this->t('@emaddr is not a valid email address.', array('@emaddr' => $emaddr)));
    }
  }

}
