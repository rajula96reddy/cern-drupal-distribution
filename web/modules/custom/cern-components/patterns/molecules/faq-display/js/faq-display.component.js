(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsFAQDisplay = {
        attach: function (context) {

            let imgSRC = jQuery('.faq-node-header-content-image').find('img').attr('src');
            let veil = 'radial-gradient(ellipse at center, rgba(0, 0, 0, 0.2) 0, rgba(0, 0, 0, 0.5) 100%),';
            let cssBG = veil+' url('+imgSRC+') no-repeat center / cover';
            jQuery('.faq-node-full-content-image').css('background', cssBG);

        }
    };
})(jQuery, Drupal);    