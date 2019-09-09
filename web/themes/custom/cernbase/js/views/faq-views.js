(function ($, Drupal) {

    'use strict';
  
    jQuery(function () {

        jQuery(".cern-view-display-page.cern-view-display-faq_page form.views-exposed-form .form-item:first-child").each(function(){
            var item = jQuery(this);
            var placeholder = item.find('label').text(); 
            item.find('label').hide();
            item.find('input').attr('placeholder', placeholder.toUpperCase());
            setTimeout(function(){
                item.find('input').removeAttr('title');
                item.find('input').removeAttr('data-original-title');
            }, 1000);
        });   
        jQuery(".cern-view-display-more_faq .view-content .views-row").each(function(){
            var bgItem = jQuery(this).find('.faq-box-pattern').data('background');
            jQuery(this).css('background', bgItem);
        });
        

    });

})(jQuery, Drupal);


