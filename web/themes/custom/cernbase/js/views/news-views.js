
(function ($, Drupal) {

    'use strict';
  
    jQuery(function () {
        /* jQuery(".cern-page-display-page .block-views-exposed-filter-blockcern-all-news-all-news .form-item:first-child").each(function(){
            var placeholder = jQuery(this).find('label').text(); 
            jQuery(this).find('label').hide();
            jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
        });  */
        
        var items = 0;
        var cintval = setInterval(function(){
            items =  jQuery(".path-news .cern-page-display-page .views-exposed-form .form-item:first-child");
            if (items.length > 0) {
                jQuery(".cern-page-display-page .block-views-exposed-filter-blockcern-all-news-all-news .form-item:first-child").each(function(){
                    var placeholder = jQuery(this).find('label').text(); 
                    jQuery(this).find('label').hide();
                    jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
                }); 
                clearInterval(cintval);
            }
        }, 500); 

        //.cern-view-display-page.cern-view-display-all_news_filter > h2, 
        jQuery(".cern-view-display-page.cern-view-display-all_news > h2").addClass("text-center");
        jQuery(".cern-view-display-page.cern-view-display-all_news > h2").addClass("mb-2");
        jQuery(".cern-view-display-page.cern-view-display-all_news > h2").insertBefore(jQuery(".cern-view-display-page.cern-view-display-all_news > h2").closest('.region-content'));
        
    });

})(jQuery, Drupal);
