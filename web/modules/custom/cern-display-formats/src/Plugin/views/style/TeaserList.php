<?php

namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;
/**
 * Style plugin rendering as Teaser List
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "teaser_list",
 *   title = @Translation("Teaser List"),
 *   help = @Translation("Displays content using the Teaser List style"),
 *   theme = "views_view_teaser_list",
 *   display_types = {"normal"}
 * )
 */
class TeaserList extends DefaultStyle
{
    /**
     * {@inheritdoc}
     */
    protected $usesRowPlugin = true;

    //the format should not use row classes
    protected $usesRowClass = true;


    protected $usesoptions = true;


	/**
	 * Overrides the options form of Teaser List
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}

}
