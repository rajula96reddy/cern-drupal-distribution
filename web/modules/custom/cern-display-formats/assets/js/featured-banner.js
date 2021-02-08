(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.FeaturedBannerBehavior = {
        attach: function (context, settings) {
            // can access setting from 'drupalSettings';
            $('.featured-banner .featured-title a', context).once().css('cssText','color: '+ drupalSettings.displayFormats.featured_banner.linkColor+ ';');
            $('.featured-banner .preview-cards__subtext *', context).once().css('cssText','color: '+ drupalSettings.displayFormats.featured_banner.subtextColor+ ';');
            $('.featured-banner .component-preview-cards', context).once().css('cssText','background-color: '+ drupalSettings.displayFormats.featured_banner.backgroundColor+ ';');
        }
    };
})(jQuery, Drupal, drupalSettings);