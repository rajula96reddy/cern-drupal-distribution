(function ($, Drupal) {

    'use strict';
  
    $(function () {
        if (jQuery('a.facebook-msg.share')) {
            jQuery('a.facebook-msg.share').closest('li').hide();
        }
        if (jQuery('a.pinterest.share')) {
            jQuery('a.pinterest.share').closest('li').hide();
        }
    });

    /* var footerTop = jQuery('footer').position().top;
    var footerHeight = jQuery('footer').outerHeight();
    var socialIconsTop = jQuery('.block-social-media.block-social-sharing-block').position().top;
    var socialIconsHeight = jQuery('.block-social-media.block-social-sharing-block').height();

    $(function () {
        footerTop = jQuery('footer').position().top;
        footerHeight = jQuery('footer').outerHeight();
        socialIconsTop = jQuery('.block-social-media.block-social-sharing-block').position().top;
        socialIconsHeight = jQuery('.block-social-media.block-social-sharing-block').height();
    }); */

    $( window ).scroll(function() {
        if (jQuery('footer').length > 0) {
            var footerTop = jQuery('footer').position().top;
            var footerHeight = jQuery('footer').outerHeight();
            if (jQuery('body').hasClass('.user-logged-in')) {
                var socialIconsTop = 280;
            } else {
                var socialIconsTop = 200;
            }
            //var socialIconsTop = jQuery('.block-social-media.block-social-sharing-block').position().top;
            var socialIconsHeight = jQuery('.block-social-media.block-social-sharing-block').height();
            if ((jQuery(window).scrollTop() + socialIconsTop + socialIconsHeight + 100) > footerTop) {
                var newFooterTop = footerTop - jQuery(window).scrollTop() - socialIconsHeight - 20;
                //document.getElementsByClassName('block-social-media block-social-sharing-block').style.top = newFooterTop + 'px';
                var elem = document.querySelector('.block-social-media.block-social-sharing-block');
                elem.style.top = newFooterTop + 'px'; //('top', newFooterTop + 'px');
            } else {            
                jQuery('.block-social-media.block-social-sharing-block').css('top', '');
            }
        }
    });
    
})(jQuery, Drupal);
