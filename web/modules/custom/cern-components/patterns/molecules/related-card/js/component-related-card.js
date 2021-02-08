(function (jQuery, Drupal) {

    'use strict';

    jQuery(function() {
        jQuery(".component-related_card").hover(function(){
            jQuery(this).find(".component-related_card__content__link a").addClass("hovered")
        }, function () {
            jQuery(this).find(".component-related_card__content__link a").removeClass("hovered")
        });
    });


})(jQuery, Drupal);
