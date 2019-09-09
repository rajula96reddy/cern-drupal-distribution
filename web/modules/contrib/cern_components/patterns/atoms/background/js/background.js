(function ($, Drupal) {
  'use strict';
  Drupal.behaviors.CERNComponentsBackground = {
     attach: function (context, settings) {
      if ($('.background-component.background__cds_media').length) {
        $('.background-component.background__cds_media').each(function () {
          var image_link = $(this).find('figure a');
          if (image_link.length > 0) {
            image_link.css('cursor', 'default');
            image_link.css('pointer-events', 'none');
            image_link.on('click', function(e){
              e.preventDefault();
            });
          }
        });
      }
    }
  }
})(jQuery, Drupal);
