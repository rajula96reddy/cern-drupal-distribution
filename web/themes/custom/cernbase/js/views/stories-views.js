(function ($, Drupal) {

    'use strict';
  

    jQuery(function() {
        calculateRelatedStoryBlocksHeight();
    });
    jQuery( window ).resize(function() {
        calculateRelatedStoryBlocksHeight();
    });

    function calculateRelatedStoryBlocksHeight() {
        jQuery(".cern-view-display-block.cern-view-display-related_stories_block .carousel-cern-item.row .views-row").each(function(){
            jQuery(this).css('height', (jQuery(this).find(".component-preview-cards").height() + 60) + "px");
        });
    }

})(jQuery, Drupal);
