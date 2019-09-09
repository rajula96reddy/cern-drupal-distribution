(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsFAQDisplay = {
        attach: function (context) {

            let imgSRC = jQuery('.faq-node-header-content-image').find('img').attr('src');
            let cssBG = '#999999 url('+imgSRC+') no-repeat center top / cover';
            jQuery('.faq-node-full-content-image').css('background', cssBG);

        }
    };
})(jQuery, Drupal);    