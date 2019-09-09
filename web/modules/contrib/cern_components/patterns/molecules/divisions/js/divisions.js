(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.CERNComponentsDivisions = {
    attach: function (context) {

      divisionPosition();

      $(window).on('resize', function () {
        divisionPosition();
      });

      function divisionPosition() {
        if ($('.component-division').length > 0) {
          $('.component-division').each(function () {
            if ($(this).find('.component-division__text').length > 0) {
              var divisionHeight = $(this).outerHeight(true);
              var divisionTextHeight = $(this).find('.component-division__text').outerHeight(true);
              var topMargin = divisionHeight - divisionTextHeight;
              if ($(this).hasClass('bottomright') || $(this).hasClass('bottomleft')) {
                $(this).find('.component-division__text').css('top', topMargin);
              }
            }
          });
        }
      }
    }
  };

})(jQuery, Drupal, drupalSettings);
