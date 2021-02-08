(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.AccordionBehavior = {
        attach: function (context, settings) {
            // can access setting from 'drupalSettings';
            $('.accordion-cern .panel-heading a', context).once().css('cssText','color: '+ drupalSettings.displayFormats.accordion.titleColor+ ';');
        }
    };
})(jQuery, Drupal, drupalSettings);