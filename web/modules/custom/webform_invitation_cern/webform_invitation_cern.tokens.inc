<?php

/**
 * @file
 * webform_invitation tokens.
 */

use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;

/**
 * Implements hook_token_info().
 */
function webform_invitation_cern_token_info() {
  $info = [];
  $info['types']['webform_invitation'] = [
    'name' => t('Webform invitation'),
    'description' => ('Webform invitation tokens.'),
  ];

  $info['tokens']['webform_invitation']['code:url'] = [
    'name' => t('Invitation code url.'),
    'description' => t('Link to the webform submission page including the code.'),
  ];

  return $info;
}

/**
 * Implements hook_tokens().
 */
function webform_invitation_cern_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];
  if ($type == 'webform_invitation') {

    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'code:url':
          $replacement = "";
          if (is_array($data["url"])) {
            foreach( $data["url"] as $url) {
              $replacement .= "<p><a href=\"$url\">$url</a></p>";
            }
          }
          else {
            $replacement = '<a href="' . $data['url'] . '">' . $data['url'] . '</a>';
          }
          $replacements[$original] = \Drupal\Core\Render\Markup::create($replacement);
          break;

        default:
      }
    }
  }

  return $replacements;
}
