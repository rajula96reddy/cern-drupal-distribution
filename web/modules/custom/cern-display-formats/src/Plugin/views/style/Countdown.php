<?php


namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;

/**
 * Implements the Countdown display format
 *
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 * id = "countdown",
 * title = @Translation("Countdown"),
 * help = @Translation("Displays content using the countdown efect"),
 * theme = "views_view_unformatted",
 * display_types = {"normal"}
 * )
 */
class Countdown extends DefaultStyle {

	protected $usesoptions = true;

	protected $usesRowPlugin = true;

	//the format should not use row classes
	protected $usesRowClass = true;


	/**
	 * Defines the initial options for Countdown format
	 *
	 * @return mixed
	 */
	protected function defineOptions(){
		$options = parent::defineOptions();
		return $options;
	}

	public function buildOptionsForm(&$form, FormStateInterface $form_state){
		parent::buildOptionsForm($form, $form_state);
	}
}