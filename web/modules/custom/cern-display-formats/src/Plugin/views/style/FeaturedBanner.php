<?php


namespace Drupal\cern_display_formats\Plugin\views\style;


use Drupal\views\Plugin\views\style\DefaultStyle;
use Drupal\core\form\FormStateInterface;

/**
 * Implements the Featured Banner display format
 *
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 * id = "featured_banner",
 * title = @Translation("Featured Banner"),
 * help = @Translation("Displays content as a featured banner."),
 * theme = "views_view_unformatted",
 * display_types = {"normal"}
 * )
 */
class FeaturedBanner extends DefaultStyle {


	protected $usesoptions = true;

	protected $usesRowPlugin = true;

	protected $usesRowClass = true;

	/**
	 * Defines the initial options for the Featured Banner format
	 *
	 * @return mixed
	 */
	protected function defineOptions(){
		$options = parent::defineOptions();
		return $options;
	}

	/**
	 * Overrides the options form of Featured Banner
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}


}