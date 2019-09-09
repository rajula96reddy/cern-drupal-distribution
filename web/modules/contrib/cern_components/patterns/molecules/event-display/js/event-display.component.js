(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsEventDisplay = {
        attach: function (context) {
            jQuery(".event-node-full-content-audience a:not(:last-child):not(:nth-last-child(2))").once().after("<span>,&nbsp;</span> ");
            jQuery(".event-node-full-content-audience a:nth-last-child(2)").once().after(" <span>&nbsp;&&nbsp;</span> ");
        }  
    };
    
})(jQuery, Drupal);
