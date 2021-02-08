<?php


namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;
use Drupal\views\Plugin\views\style\UnformattedSummary;

/**
 * Implements the Events collision display format
 *
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 * id = "collision",
 * title = @Translation("Collision"),
 * help = @Translation("Displays content using the collision efect"),
 * theme = "views_view_collision",
 * display_types = {"normal"}
 * )
 */
class Collision extends DefaultStyle {

	protected $usesoptions = true;


	/**
	 * Overrides the options form of Collision display format
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state){
		parent::buildOptionsForm($form, $form_state);

		$form['row_class'] = array(
				'#type' => 'textfield',
				'#title' => t('Row Class'),
				'#description' => t(' The classes to provide on each row. Make sure to have the value <strong>events-collision-row</strong>'),
				'#default_value' => ($this->options['row_class'] == '' ? 'events-collision-row' : $this->options['row_class']),
				'#disabled' => false,
		);
	}



}