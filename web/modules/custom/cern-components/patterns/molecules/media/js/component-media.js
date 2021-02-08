(function (jQuery, Drupal) {

    'use strict';

    jQuery(function() {
        jQuery(".component-media-content").hover(function(){
            jQuery(this).find(".component-media-content__title a").addClass("hovered")
        }, function () {
            jQuery(this).find(".component-media-content__title a").removeClass("hovered")
        });
    });


})(jQuery, Drupal);
