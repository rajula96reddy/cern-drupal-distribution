<?php


namespace Drupal\cern_display_formats\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Sets administrative form for CERN Display Formats
 *
 * Class DisplayFormatsSettingsForm
 * @package Drupal\cern_display_formats\Form
 */
class DisplayFormatsSettingsForm extends ConfigFormBase {

	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames() {
		return [
				'cern_display_formats.adminsettings',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'cern_display_formats_settings';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$config = $this->config('cern_display_formats.adminsettings');

		//	Accordion form settings
		$form['accordion'] = array(
			'#type' => 'details',
			'#title' => $this->t('Accordion Format'),
			'#open' => TRUE,
		);

		$form['accordion']['accordion_title_color'] = array(
				'#type' => 'color',
				'#title' => t('Title Color'),
				'#description' => t('Choose the color that you want the Accordion title to have. Defaults to #000000. '),
				'#default_value' => (!empty($config->get('accordion_title_color'))) ? $config->get('accordion_title_color') : '#000000',
		);

		//	Countdown form settings
		$form['countdown'] = array(
				'#type' => 'details',
				'#title' => $this->t('Countdown Display Format'),
				'#open' => TRUE,
		);

		$form['countdown']['countdown_title_color'] = array(
				'#type' => 'color',
				'#title' => t('Countdown View Title Color'),
				'#description' => t('Choose the color that you want the Countdown view title to have. Defaults to white.'),
				'#default_value' => (!empty($config->get('countdown_title_color'))) ? $config->get('countdown_title_color') : '#FFFFFF',
		);

		$form['countdown']['countdown_date_color'] = array(
				'#type' => 'color',
				'#title' => t('Countdown Date Color'),
				'#description' => t('Choose the color that you want the Countdown date to be. Defaults to white.'),
				'#default_value' => (!empty($config->get('countdown_date_color'))) ? $config->get('countdown_date_color') : '#FFFFFF',
		);

		$form['countdown']['countdown_link_color'] = array(
				'#type' => 'color',
				'#title' => t('Countdown Link Color'),
				'#description' => t('Choose the color that you want the Countdown link to be. Defaults to white.'),
				'#default_value' => (!empty($config->get('countdown_link_color'))) ? $config->get('countdown_link_color') : '#FFFFFF',
		);

		$form['countdown']['countdown_place_color'] = array(
				'#type' => 'color',
				'#title' => t('Countdown Place Color'),
				'#description' => t('Choose the color that you want the Countdown place to be. Defaults to white.'),
				'#default_value' => (!empty($config->get('countdown_place_color'))) ? $config->get('countdown_place_color') : '#FFFFFF',
		);

		//	Featured Banner form settings
		$form['featured_banner'] = array(
				'#type' => 'details',
				'#title' => $this->t('Featured Banner Display Format'),
				'#open' => TRUE,
		);

		$form['featured_banner']['featured_banner_link_color'] = array(
				'#type' => 'color',
				'#title' => t('Featured Banner link title'),
				'#description' => t('Choose the color that you want the Featured Banner link titles to have. Defaults to blue.'),
				'#default_value' => (!empty($config->get('featured_banner_link_color'))) ? $config->get('featured_banner_link_color') : '#2574b9',
		);

		$form['featured_banner']['featured_banner_subtext_color'] = array(
				'#type' => 'color',
				'#title' => t('Featured Banner Subtext Color'),
				'#description' => t('Choose the color that you want the Featured Banner subtext to have. Defaults to black.'),
				'#default_value' => (!empty($config->get('featured_banner_subtext_color'))) ? $config->get('featured_banner_subtext_color') : '#000000',
		);

		$form['featured_banner']['featured_banner_background_color'] = array(
				'#type' => 'color',
				'#title' => t('Featured Banner Background Color'),
				'#description' => t('Choose the color that you want the Featured Banner background color to be. Defaults to white.'),
				'#default_value' => (!empty($config->get('featured_banner_background_color'))) ? $config->get('featured_banner_background_color') : '#FFFFFF',
		);




		return parent::buildForm($form, $form_state);
	}


	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		parent::submitForm($form, $form_state);

		$this->config('cern_display_formats.adminsettings')
				->set('accordion_title_color', $form_state->getValue('accordion_title_color'))
				->set('teaser_list_title_color', $form_state->getValue('teaser_list_title_color'))
				->set('teaser_list_title_color_card', $form_state->getValue('teaser_list_title_color_card'))
				->set('countdown_date_color', $form_state->getValue('countdown_date_color'))
				->set('countdown_link_color', $form_state->getValue('countdown_link_color'))
				->set('countdown_place_color', $form_state->getValue('countdown_place_color'))
				->set('countdown_title_color', $form_state->getValue('countdown_title_color'))
				->set('featured_banner_link_color', $form_state->getValue('featured_banner_link_color'))
				->set('featured_banner_subtext_color', $form_state->getValue('featured_banner_subtext_color'))
				->set('featured_banner_background_color', $form_state->getValue('featured_banner_background_color'))
				->save();
		\Drupal::service('cache.render')->invalidateAll();	// clears the caches
	}


}