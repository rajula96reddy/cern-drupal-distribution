(function ($, Drupal) {

    'use strict';
  
    jQuery(function () {
        var maxHeight = 0;
        jQuery(".cern-view-display-page.cern-view-display-feature_events .view-content .views-row")
        .each(function() {
            maxHeight = (jQuery(this).height() > maxHeight) ? jQuery(this).height() : maxHeight;
        });

        jQuery(".cern-view-display-page.cern-view-display-past_events .view-content .views-row")
        .each(function() {
            maxHeight = (jQuery(this).height() > maxHeight) ? jQuery(this).height() : maxHeight;
        });

        jQuery(".cern-view-display-page.cern-view-display-feature_events .view-content .views-row")
        .each(function() {
            jQuery(this).css('height', maxHeight+"px");
            jQuery(this).find('.agenda-box-pattern').css('height', '100%');
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button a').addClass('btn').addClass('btn-primary');
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button').show();
        });  

        jQuery(".cern-view-display-page.cern-view-display-past_events .view-content .views-row")
        .each(function() {
            jQuery(this).css('height', maxHeight+"px");
            jQuery(this).find('.agenda-box-pattern').css('height', '100%');
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button a').addClass('btn').addClass('btn-primary');
            jQuery(this).find('.agenda-box-pattern').find('.agenda-box-cal-button').show();
        });       

        jQuery(".cern-view-display-page.cern-view-display-past_events form.views-exposed-form .form-item:first-child").each(function(){
            var placeholder = jQuery(this).find('label').text(); 
            jQuery(this).find('label').hide();
            jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
        }); 

        jQuery(".cern-view-display-page.cern-view-display-feature_events form.views-exposed-form .form-item:first-child").each(function(){
            var placeholder = jQuery(this).find('label').text(); 
            jQuery(this).find('label').hide();
            jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
        });   

    });

})(jQuery, Drupal);


