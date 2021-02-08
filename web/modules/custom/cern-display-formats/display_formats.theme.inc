<?php

use Drupal\Core\Template\Attribute;


/**
 * @file
 *
 */
function template_preprocess_views_view_horizontal_boxes(&$variables) {
    $view = $variables['view'];
    $rows = $variables['rows'];
    $style = $view->style_plugin;
    $options = $style->options;

    $variables['default_row_class'] = !empty($options['default_row_class']);
    foreach ($rows as $id => $row) {
        $variables['rows'][$id] = [];
        $variables['rows'][$id]['content'] = $row;
        $variables['rows'][$id]['attributes'] = new Attribute();
        if ($row_class = $view->style_plugin->getRowClass($id)) {
            $variables['rows'][$id]['attributes']->addClass($row_class);
        }
    }
}


/**
 * Preprocess function of the Events Collision template.
 *
 * @file
 *
 */
function template_preprocess_views_view_collision(&$variables) {
	$view = $variables['view'];
	$rows = $variables['rows'];
	$style = $view->style_plugin;
	$options = $style->options;

	$variables['default_row_class'] = !empty($options['default_row_class']);
	foreach ($rows as $id => $row) {
		$variables['rows'][$id] = [];
		$variables['rows'][$id]['content'] = $row;
		$variables['rows'][$id]['attributes'] = new Attribute();
		if ($row_class = $view->style_plugin->getRowClass($id)) {
			$variables['rows'][$id]['attributes']->addClass($row_class);
		}
	}
}

/**
 * @file
 *
 */
function template_preprocess_views_view_teaser_list(&$variables) {
    $view = $variables['view'];
    $rows = $variables['rows'];
    $style = $view->style_plugin;
    $options = $style->options;

    $variables['default_row_class'] = !empty($options['default_row_class']);
    foreach ($rows as $id => $row) {
        $variables['rows'][$id] = [];
        $variables['rows'][$id]['content'] = $row;
        $variables['rows'][$id]['attributes'] = new Attribute();
        if ($row_class = $view->style_plugin->getRowClass($id)) {
            $variables['rows'][$id]['attributes']->addClass($row_class);
        }
    }
}