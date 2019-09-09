(function ($, Drupal) {

  'use strict';

  $(function () {
    var height = 0;
    var cintval = setInterval(function () {
      height = jQuery(".cern-timeline").contents().find("body").height();
      if (height > 0) {
        jQuery(".cern-timeline").css('height', height + 'px');
        clearInterval(cintval);
      }
    }, 500);
  });

  $(window).resize(function () {
    $("iframe.cern-timeline").each(function() {
      var src = $(this).attr('src');
      $(this).attr('src', src);
    });
  });

})(jQuery, Drupal);
