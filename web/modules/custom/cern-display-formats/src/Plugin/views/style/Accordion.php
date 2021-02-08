<?php

namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;
/**
 * Style plugin rendering as Accordions
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "accordion",
 *   title = @Translation("Accordion"),
 *   help = @Translation("Displays content in an accordion style"),
 *   theme = "views_view_unformatted",
 *   display_types = {"normal"}
 * )
 */
class Accordion extends DefaultStyle {

	protected $usesRowPlugin = true;

	/**
	 * Does the style plugin support custom css class for the rows.
	 *
	 * @var bool
	 */
	protected $usesRowClass = true;

	protected $usesoptions = true;

	/**
	 * Whether the format will allow rendering fields
	 *
	 * @var bool
	 */
	protected $renderFields = true;

	/**
	 * Whether the format allows grouping
	 *
	 * @var bool
	 */
	protected $usesgrouping = true;

	/**
	 * Defines the initial options of the Horizontal Boxes.
	 *
	 * @return mixed
	 */
	protected function defineOptions(){
		$options = parent::defineOptions();
		return $options;
	}

	/**
	 * Overrides the options form of Accordion
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}

}