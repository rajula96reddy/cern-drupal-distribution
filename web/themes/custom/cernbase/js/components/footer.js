(function ($, Drupal) {

    'use strict';

    jQuery(function () {
        checkFooterHeight();
    });
    
    jQuery( window ).resize(function() {
        checkFooterHeight();
    });

    function checkFooterHeight() {
        if (jQuery(window).width() >= 768) {
            jQuery('footer .footer-first-col').css('height', '');
            setTimeout(function(){
                var minHeightFooterFirstCol = jQuery('footer .row.cern-footer').height() - 15;
                jQuery('footer .footer-first-col').css('height', minHeightFooterFirstCol + 'px');
            }, 1000);
        }
    }
  
})(jQuery, Drupal);
