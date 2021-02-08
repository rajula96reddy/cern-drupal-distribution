<?php

namespace Drupal\cern_toolbar\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;

/**
 * Class CernToolbarSettingsForm.
 */
class CernToolbarSettingsForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cern_toolbar_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This form contains the configuration of the top CERN Toolbar.'),
    ];
    $config = \Drupal::config('cern_toolbar.settings');
    // Gather the number of additional links in the form already.
    $num_additional_links = $form_state->get('num_additional_links');
    // We have to ensure that there is at least one name field.
    if ($num_additional_links === NULL) {
      $num_additional_links = count($config->getRawData());
      $num_additional_links = $num_additional_links > 0 ? $num_additional_links / 2 : 1;
      $additional_link_field = $form_state->set('num_additional_links', $num_additional_links);
    }
    $form['#tree'] = TRUE;
    $form['additional_links_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Additional Links'),
      '#prefix' => '<div id="additional-links-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    for ($i = 0; $i < $num_additional_links; $i++) {
      $form['additional_links_fieldset']['additional_link_'.$i] = [
        '#type' => 'url',
        //'#required' => TRUE,
        '#title' => '#'. ($i+1) .' '.$this->t('Additional link url'),
        '#description' => $this->t('Enter additional sign in link URL'),
        '#default_value' => $config->get('additional_link_'.$i),
      ];
      $form['additional_links_fieldset']['additional_link_title_'.$i] = [
        '#type' => 'textfield',
        //'#required' => TRUE,
        '#title' => '#'. ($i+1) .' '.$this->t('Additional link title'),
        '#description' => $this->t('Enter additional sign in link title'),
        '#default_value' => $config->get('additional_link_title_'.$i),
      ];
    }
    $form['additional_links_fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    $form['additional_links_fieldset']['actions']['add_additional_link'] = [
      '#type' => 'submit',
      '#value' => t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'additional-links-fieldset-wrapper',
      ],
    ];
    // If there is more than one name, add the remove button.
    if ($num_additional_links > 0) {
      $form['additional_links_fieldset']['actions']['remove_name'] = [
        '#type' => 'submit',
        '#value' => t('Remove one'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'additional-links-fieldset-wrapper',
        ],
      ];
    }

		$moduleHandler = \Drupal::service('module_handler');
		if ( $moduleHandler->moduleExists('cookieconsent')){
			$form['cookies_fieldset'] = [
					'#type' => 'fieldset',
					'#title' => $this->t('Cookies settings'),
			];

			$form['cookies_fieldset']['cookieconsent'] = [
					'#markup' => $this->t('To configure the cookies banner please enter the configuration of the module '. Link::createFromRoute('Cookie Consent', 'cookieconsent.cookieconsent_admin_form')->toString())
			];
		}


    $form_state->setCached(FALSE);
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('cern_toolbar.settings');
    $config->delete();
    foreach($form_state->getValue('additional_links_fieldset') as $key => $value){
      if (strpos($key, 'additional_link') !== FALSE) {
        if (!empty($value)){
          $config->set($key, $value);
        }
      }
    }
    $config->save();
    Cache::invalidateTags(['cern_toolbar']);
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the additional links in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['additional_links_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $additional_link_field = $form_state->get('num_additional_links');
    $add_button = $additional_link_field + 1;
    $form_state->set('num_additional_links', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $additional_link_field = $form_state->get('num_additional_links');
    if ($additional_link_field > 0) {
      $remove_button = $additional_link_field - 1;
      $form_state->set('num_additional_links', $remove_button);
    }
    $form_state->setRebuild();
  }


}
