<?php


namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;

/**
 * Style plugin rendering as Event Grid
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "event_grid",
 *   title = @Translation("Event Grid"),
 *   help = @Translation("Displays content as an Event Grid"),
 *   theme = "views_view_unformatted",
 *   display_types = {"normal"}
 * )
 */
class EventGrid extends DefaultStyle {

	protected $usesRowPlugin = true;

	/**
	 * Does the style plugin support custom css class for the rows.
	 *
	 * @var bool
	 */
	protected $usesRowClass = true;

	protected $usesoptions = true;

	protected $renderFields = true;

	/**
	 * Overrides the options form of Event Grid
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
	}
}