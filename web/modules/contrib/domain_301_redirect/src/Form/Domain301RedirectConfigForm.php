<?php

namespace Drupal\domain_301_redirect\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\domain_301_redirect\Domain301RedirectManager;
use Drupal\domain_301_redirect\Domain301Redirect;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a settings for domain_301_redirect module.
 */
class Domain301RedirectConfigForm extends ConfigFormBase {

  /**
   * Domain 301 Redirect Manager.
   *
   * @var \Drupal\domain_301_redirect\Domain301RedirectManager
   */
  protected $redirectManager;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\domain_301_redirect\Domain301RedirectManager $domain_301_redirect_manager
   *   The Domain301RedirectManager service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   */
  public function __construct(ConfigFactoryInterface $config_factory, Domain301RedirectManager $domain_301_redirect_manager, DateFormatterInterface $date_formatter) {
    parent::__construct($config_factory);
    $this->redirectManager = $domain_301_redirect_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('domain_301_redirect.manager'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'domain_301_redirect_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'domain_301_redirect.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('domain_301_redirect.settings');
    $disabled_by_check = $config->get('disabled_by_check');
    $enabled = $config->get('enabled');

    // Warn the user if the redirect was disabled by cron.
    if (!$enabled && $disabled_by_check) {
      $last_checked = $config->get('last_checked');
      $this->messenger()->addWarning($this->t('Redirects have been disabled by cron because the domain was not available at: %date.', [
        '%date' => $this->dateFormatter->format($last_checked),
      ]));
    }

    $form['enabled'] = [
      '#type' => 'radios',
      '#title' => $this->t('Status'),
      '#description' => $this->t('Enable this setting to start 301 redirects to the domain below for all other domains.'),
      '#options' => [
        1 => $this->t('Enabled'),
        0 => $this->t('Disabled'),
      ],
      '#default_value' => $config->get('enabled'),
    ];

    $form['domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Domain'),
      '#description' => $this->t('This is the domain that all other domains pointing to this site will be 301 redirected to. This value should also include the scheme such as http or https but will redirect to http if not specified. Example: http://www.testsite.com'),
      '#default_value' => $config->get('domain'),
    ];

    // Per path configuration settings to apply the redirect to specific paths.
    $form['domain_check'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Domain check'),
      '#open' => TRUE,
    ];

    $form['domain_check']['check_period'] = [
      '#type' => 'select',
      '#title' => $this->t('Check period'),
      '#description' => $this->t('This option selects the period between domain validation checks. If the domain no longer points to this site, domain redirection will be disabled.'),
      '#options' => [
        0 => $this->t('Disabled'),
        3600 => $this->t('1 hour'),
        7200 => $this->t('2 hours'),
        10800 => $this->t('3 hours'),
        21600 => $this->t('6 hours'),
        43200 => $this->t('12 hours'),
        86400 => $this->t('1 day'),
      ],
      '#default_value' => $config->get('check_period'),
    ];

    $form['domain_check']['domain_check_retries'] = [
      '#type' => 'select',
      '#title' => $this->t('Retries'),
      '#description' => $this->t('Number of times to check domain availability before disabling redirects.'),
      '#options' => [1 => 1, 2 => 2, 3 => 3],
      '#default_value' => $config->get('domain_check_retries'),
    ];

    $form['domain_check']['domain_check_reenable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Re-enable domain redirection'),
      '#description' => $this->t('Turn domain redirection on when the domain becomes available.'),
      '#default_value' => $config->get('domain_check_reenable'),
    ];

    // Per path configuration settings to apply the redirect to specific paths.
    $form['applicability'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Pages'),
      '#open' => TRUE,
    ];

    $form['applicability']['pages'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('pages'),
      '#description' => $this->t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", [
        '%blog' => '/blog',
        '%blog-wildcard' => '/blog/*',
        '%front' => '<front>',
      ]),
    ];

    $form['applicability']['applicability'] = [
      '#type' => 'radios',
      '#options' => [
        Domain301Redirect::EXCLUDE_METHOD => $this->t('Do not redirect for the listed pages'),
        Domain301Redirect::INCLUDE_METHOD => $this->t('Only redirect for the listed pages'),
      ],
      '#default_value' => $config->get('applicability'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('domain_301_redirect.settings');

    $values = $form_state->getValues();
    if (!empty($values['enabled'])) {
      // Clean & trim the domain.
      $domain = trim(trim($values['domain']), '/');

      if (!preg_match('|^https?://|', $domain)) {
        $domain = 'http://' . $domain;
      }
      if (!UrlHelper::isValid($domain, TRUE)) {
        $form_state->setErrorByName('domain', $this->t('Domain 301 redirection can not be enabled if a valid domain is not set.'));
      }
      else {
        $domain_parts = parse_url($domain);
        $domain = $domain_parts['scheme'] . '://' . $domain_parts['host'] . (!empty($domain_parts['port']) ? ':' . $domain_parts['port'] : '');
        $form_state->set('domain', $domain);

        if (!$this->redirectManager->checkDomain($domain)) {
          $form_state->setErrorByName('enabled', $this->t('Domain 301 redirection can not be enabled as the domain you set does not currently point to this site.'));
        }
        else {
          // Clean up if someone is manually disabling. We don't want the system
          // to re-enable if the disabling was via the admin form.
          $this->config('domain_301_redirect.settings')
            ->set('disabled_by_check', FALSE)
            ->save();
        }

        $redirect_count = 0;
        if ($this->redirectManager->checkRedirectLoop($domain, $redirect_count)) {
          $form_state->setErrorByName('domain', $this->t('Domain 301 redirection can not be enabled as the domain you set does not currently point to this site.', ['@num' => $config->get('loop_max_redirects')]));
        }
        elseif ($redirect_count > 0) {
          $this->messenger()->addWarning($this->t('The redirect domain follows @count redirects. You may want to specify the final domain.', [
            '@count' => $redirect_count,
          ]));
        }
      }
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('domain_301_redirect.settings')
      ->set('enabled', $values['enabled'])
      ->set('domain', $values['domain'])
      ->set('check_period', $values['check_period'])
      ->set('domain_check_retries', $values['domain_check_retries'])
      ->set('domain_check_reenable', $values['domain_check_reenable'])
      ->set('applicability', $values['applicability'])
      ->set('pages', $values['pages'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
