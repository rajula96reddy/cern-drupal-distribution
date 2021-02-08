<?php


namespace Drupal\cern_display_formats\Plugin\views\style;


use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;

/**
 * Implements the "Vertical Boxes" display format
 *
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 * id = "vertical_boxes",
 * title = @Translation("Vertical Boxes"),
 * help = @Translation("Displays content in a format of Vertical Boxes"),
 * theme = "views_view_unformatted",
 * display_types = {"normal"}
 * )
 */
class VerticalBoxes extends DefaultStyle {

	/**
	 * {@inheritdoc}
	 */
	protected $usesRowPlugin = true;

	protected $usesRowClass = true;

	protected $usesoptions = true;

	protected $renderFields = true;


	/**
	 * Defines the initial options for Vertical Boxes format
	 *
	 * @return mixed
	 */
	protected function defineOptions(){
		$options = parent::defineOptions();
		return $options;
	}

	/**
	 * Overrides the options form of Vertical Boxes
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}
}