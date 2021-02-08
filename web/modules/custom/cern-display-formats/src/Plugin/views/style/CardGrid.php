<?php


namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;

/**
 * Style plugin rendering as Card Grid
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "card_grid",
 *   title = @Translation("Card Grid"),
 *   help = @Translation("Displays content as a Card Grid"),
 *   theme = "views_view_unformatted",
 *   display_types = {"normal"}
 * )
 */
class CardGrid extends DefaultStyle {

	protected $usesRowPlugin = true;

	/**
	 * Does the style plugin support custom css class for the rows.
	 *
	 * @var bool
	 */
	protected $usesRowClass = true;

	/**
	 * Whether the format uses options
	 *
	 * @var bool
	 */
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
		 * Overrides the options form of Card Grid
		 *
		 * @param $form
		 * @param FormStateInterface $form_state
		 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}



	}