
(function ($, Drupal) {

    'use strict';
  
    jQuery(function () {
        // Placeholder of exposed forms
        jQuery(".cern-view-display-page.view-general-search form.views-exposed-form .form-item:first-child").each(function(){
            var placeholder = jQuery(this).find('label').text(); 
            jQuery(this).find('label').hide();
            jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
        }); 

        // Auto height of images
        setTimeout(function() {
            jQuery(".cern-view-display-page.view-general-search .view-content .views-row").each(function() {
                var containerHeight = jQuery(this).find(".preview-list-component").height();
                jQuery(this).find(".preview-list-image").css('min-height', containerHeight + 'px');
            });
        }, 1000);
    });

    jQuery(window).resize(function() {
        jQuery(".cern-view-display-page.view-general-search .view-content .views-row").each(function() {
            jQuery(this).find(".preview-list-image").css('min-height', '');
            var containerHeight = jQuery(this).find(".preview-list-component").height();
            jQuery(this).find(".preview-list-image").css('min-height', containerHeight + 'px');
        });
    });

})(jQuery, Drupal);
