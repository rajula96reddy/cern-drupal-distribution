(function (jQuery, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsStoryPage = {
        attach: function (context) {
            jQuery(".story-page-node-layout-content .story-page-node--byline a:not(:last-child):not(:nth-last-child(2))").once().after("<span>, </span>");
            jQuery(".story-page-node-layout-content .story-page-node--byline a:nth-last-child(2)").once().after("<span> & </span>");
        }  
    };
    
})(jQuery, Drupal);
