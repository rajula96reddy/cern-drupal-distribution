(function ($, Drupal) {

    'use strict';
  
    jQuery(function () {

        jQuery(".cern-view-display-page.cern-view-display-page_taxonomies .view-content .views-row")
        .each(function() {
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button a').addClass('btn').addClass('btn-primary');
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button').show();
        });           

    });

})(jQuery, Drupal);


