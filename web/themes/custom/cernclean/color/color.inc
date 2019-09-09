<?php

/**
 * @file
 * Lists available colors and color schemes for the cernbase theme.
 */

$info = [
  // Available colors and color labels used in theme.
  'fields' => [
    'header-background' => t('HEADER: Background'),
    'header-name' => t('HEADER: Site name text'),

    'menu-link' => t('MENU: Link'),

    'text-color' => t('BODY: text'),
    'link-color' => t('BODY: link'),
    'link-color-hover' => t('BODY: link on hover'),
    'body-bg' => t('BODY: Background'),
    'html-u-underline' => t('BODY: Line in u tag & list bullets'),
    'html-caption' => t('BODY: Caption text'),
    'html-hr' => t('BODY: Horizontal line'),

    'tabs-active-background' => t('TABS: Active tab background'),
    'tabs-active-text' => t('TABS: Active tab text'),
    'tabs-inactive-background' => t('TABS: Inactive tab background'),
    'tabs-inactive-text' => t('TABS: Inactive tab text'),

    'table-background' => t('TABLE: background'),
    'table-header-background' => t('TABLE: header background'),
    'table-header-color' => t('TABLE: header text'),
    'table-row-even' => t('TABLE: Rows even background'),
    'table-row-odd' => t('TABLE: Rows odd background'),
    'table-row-text' => t('TABLE: Rows text'),
    'table-footer-background' => t('TABLE: Footer background'),
    'table-footer-text' => t('TABLE: Footer text'),

    'icons-slider-navigation-active' => t('ICONS: Slider navigation active'),
    'icons-slider-navigation-inactive' => t('ICONS: Slider navigation inactive'),
    'icons-chevrons-underline' => t('ICONS: Chevrons & animated underline'),
    'icons-blockquotes' => t('ICONS: Blockquotes (CKEditor) '),
    'icons-slider-arrow-active' => t('ICONS: Slider navigation arrows active'),
    'icons-slider-arrow-inactive' => t('ICONS: Slider navigation arrows inactive'),

    'views-cards-background' => t('VIEWS: Card background'),
    'views-cards-title' => t('VIEWS: Card title'),
    'views-cards-color' => t('VIEWS: Card text'),

    'tag-background' => t('TAXONOMY TERMS: Tag background'),
    'tag-color' => t('TAXONOMY TERMS: Tag text'),

    'buttons-border' => t('BUTTONS: border'),
    'buttons-primary-background' => t('BUTTONS / PRIMARY: background'),
    'buttons-primary-text' => t('BUTTONS / PRIMARY: text'),
    'buttons-primary-background-hover' => t('BUTTONS / PRIMARY: backgroun hover'),
    'buttons-primary-text-hover' => t('BUTTONS / PRIMARY: text hover'),
    'buttons-secondary-background' => t('BUTTONS / SECONDARY: background'),
    'buttons-secondary-text' => t('BUTTONS / SECONDARY: text'),
    'buttons-secondary-background-hover' => t('BUTTONS / SECONDARY: background hover'),
    'buttons-secondary-text-hover' => t('BUTTONS / SECONDARY: text hover'),

    'footer-background' => t('FOOTER: Background'),
    'footer-text' => t('FOOTER: Text'),
    'footer-link' => t('FOOTER: Link'),
    'footer-link-hover' => t('FOOTER: Link on hover')
  ],
  // Pre-defined color schemes.
  'schemes' => [
    'default' => [
      'title' => t('CERN schema (default)'),
      'colors' => [
        'header-background' => '#343a40',
        'header-name' => '#fafafa',
        'menu-link' => '#fffffe',

        'text-color' => '#292929',
        'link-color' => '#2574b9',
        'link-color-hover' => '#044873',
        'body-bg' => '#ffffff',
        'html-u-underline' => '#292920',
        'html-caption' => '#aaaaaa',
        'html-hr' => '#cacaca',

        'tabs-active-background' => '#f2f6fa',
        'tabs-active-text' => '#1161af',
        'tabs-inactive-background' => '#fffdfd',
        'tabs-inactive-text' => '#105ea9',

        'table-background' => '#fefefe',
        'table-header-background' => '#212529',
        'table-header-color' => '#efefef',
        'table-row-even' => '#e9ecef',
        'table-row-odd' => '#f9f9fa',
        'table-row-text' => '#333333',
        'table-footer-background' => '#333339',
        'table-footer-text' => '#999999',

        'icons-slider-navigation-active' => '#f1862d',
        'icons-slider-navigation-inactive' => '#dddddd',
        'icons-chevrons-underline' => '#f1862e',
        'icons-blockquotes' => '#bbbbbb',
        'icons-slider-arrow-active' => '#0855a0',
        'icons-slider-arrow-inactive' => '#bebebe',

        'views-cards-background' => '#0f5aa4',
        'views-cards-title' => '#fdfeff',
        'views-cards-color' => '#e9f5ff',

        'tag-background' => '#165e9d',
        'tag-color' => '#f2f9ff',

        'buttons-border' => '#063b6f',
        'buttons-primary-background' => '#074888',
        'buttons-primary-text' => '#fbfdff',
        'buttons-primary-background-hover' => '#595959',
        'buttons-primary-text-hover' => '#02172a',
        'buttons-secondary-background' => '#0d62b6',
        'buttons-secondary-text' => '#ececec',
        'buttons-secondary-background-hover' => '#696b6c',
        'buttons-secondary-text-hover' => '#062d53',

        'footer-background' => '#191919',
        'footer-text' => '#fffeee',
        'footer-link' => '#fffefe',
        'footer-link-hover' => '#0d72ca'
      ],
    ],
  ],

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => [
    'css/color/colors.css',
  ],

  // Files to copy.
  'copy' => [
    'logo.svg',
  ],

  // Gradient definitions.
  'gradients' => [
    [
      // (x, y, width, height).
      'dimension' => [0, 0, 0, 0],
      // Direction of gradient ('vertical' or 'horizontal').
      'direction' => 'vertical',
      // Keys of colors to use for the gradient.
      'colors' => ['top', 'bottom'],
    ],
  ],

  // Preview files.
  'preview_library' => 'cernclean/color.preview',
  'preview_html' => 'color/preview.html',

  // Attachments.
  '#attached' => [
    'drupalSettings' => [
      'color' => [
        // Put the logo path into JavaScript for the live preview.
        'logo' => theme_get_setting('logo.url', 'cernbase'),
      ],
    ],
  ],
];
