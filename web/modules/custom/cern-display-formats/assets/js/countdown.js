(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.CountdownBehavior = {
        attach: function (context, settings) {
            // can access setting from 'drupalSettings';
            $('.events-countdown h1.mb-3', context).once().css('cssText','color: '+ drupalSettings.displayFormats.countdown.titleColor+ ';');
            $('.agenda-coming-soon-date h2', context).once().css('cssText','color: '+ drupalSettings.displayFormats.countdown.dateColor+ ';');
            $('.agenda-coming-soon-link a', context).once().css('cssText','color: '+ drupalSettings.displayFormats.countdown.linkColor+ ';');
            $('.agenda-coming-soon-place', context).once().css('cssText','color: '+ drupalSettings.displayFormats.countdown.placeColor+ ';');
            $('.agenda-coming-soon-countdown h1, .agenda-coming-soon-countdown h4', context).once().css('cssText','color: '+ drupalSettings.displayFormats.countdown.dateColor+ ';');
        }
    };
})(jQuery, Drupal, drupalSettings);