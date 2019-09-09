(function (jQuery, Drupal) {

    'use strict';

    jQuery(function() {
        jQuery(".component-preview-cards .preview-card__author a").each(function(){
            jQuery(this).removeAttr('href');
        });
    });

    
})(jQuery, Drupal);
