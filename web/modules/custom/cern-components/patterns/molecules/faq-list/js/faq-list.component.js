(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsFAQList = {
      attach: function (context) {
        jQuery('.panel-title').once().on('click', function(){
          // add class for +/- symbols
          let link = jQuery(this).children('a');
          if (link.hasClass("is-open")) {
            link.removeClass('is-open');
          } else {
            link.addClass('is-open');
          }

          // expand/collapse functionality
          let child = jQuery(this).parent().parent().children('.panel-collapse');
          if (child.hasClass("in")){
            child.removeClass("in");
          }
          else{
            child.addClass("in");
          }
        });
      }
    };

})(jQuery, Drupal);