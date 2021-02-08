<?php

namespace Drupal\cern_display_formats\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;
/**
 * Style plugin rendering as Horizontal Boxes
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "horizontal_boxes",
 *   title = @Translation("Horizontal Boxes"),
 *   help = @Translation("Displays boxes one next to the other. Additionally the boxes can have a carousel effect."),
 *   theme = "views_view_horizontal_boxes",
 *   display_types = {"normal"}
 * )
 */
class HorizontalBoxes extends DefaultStyle
{
    /**
     * {@inheritdoc}
     */
    protected $usesRowPlugin = true;
    //@todo: Implement option for carousel or not.
    /**
     * Does the style plugin support custom css class for the rows.
     *
     * @var bool
     */
    protected $usesRowClass = true;

    protected $usesoptions = true;

    protected $renderFields = true;

	/**
	 * Overrides the options form of HB
	 *
	 * @param $form
	 * @param FormStateInterface $form_state
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);

		$form['row_class'] = array(
				'#type' => 'textfield',
				'#title' => t('Row Class'),
				'#description' => t(' The classes to provide on each row. Make sure to have the value <strong>horizontal-boxes-row</strong>'),
				'#default_value' => ($this->options['row_class'] == '' ? 'horizontal-boxes-row' : $this->options['row_class']),
				'#disabled' => false,
		);
	}


	}