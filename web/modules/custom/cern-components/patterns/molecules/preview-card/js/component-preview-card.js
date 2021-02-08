(function (jQuery, Drupal) {

    'use strict';

    jQuery(function() {
        jQuery(".component-preview-cards .preview-card__author a").each(function(){
            jQuery(this).removeAttr('href');
        });
        jQuery(".component-preview-cards").hover(function(){
            jQuery(this).find(".preview-card__title a").addClass("hovered")
        }, function () {
            jQuery(this).find(".preview-card__title a").removeClass("hovered")
        });
    });

    
})(jQuery, Drupal);
