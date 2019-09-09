(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsFAQList = {
        attach: function (context) {

			jQuery('body').once().on('click', '.collapseManager', function(){
				if (jQuery(this).text() == '+') {
					jQuery(this).text('-');
					jQuery(this).css('top', 'auto');
					jQuery(this).css('bottom', '-32px');
					jQuery(this).css('font-size', '42px');
				} else {
					jQuery(this).text('+');
					jQuery(this).css('top', '0');
					jQuery(this).css('bottom', 'auto');
					jQuery(this).css('font-size', '30px');
				}
				jQuery(this).closest('.faq-list-content-title').next('.faq-list-content-body').toggleClass('hidden');
			});

        }  
    };

})(jQuery, Drupal);    