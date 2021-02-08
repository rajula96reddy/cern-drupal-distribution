(function ($, Drupal) {

    'use strict';

    function offsetAnchor() {
        if(location.hash.length !== 0) {
          let scrollHeight = $('header[role="banner"]').height();
          // toolbar-tray is-active toolbar-tray-horizontal
          let toolbarTray = $('.toolbar-tray-horizontal.is-active');
          let adminToolbar= $('#toolbar-bar');
          let cernToolbar = $('#cern-toolbar');

          // if admin toolbar exists
          if (adminToolbar.length){
            scrollHeight += adminToolbar.height();
          }
          // if admin toolbar tray is open
          if (toolbarTray.length){
            scrollHeight += toolbarTray.height();
          }
          // if cern toolbar is visible
          if ($('body').hasClass('sticky-header') === false){
            scrollHeight += cernToolbar.height() + 50;
          }

          window.scrollTo(window.scrollX, window.scrollY - scrollHeight);
        }
    }
    $(window).on("hashchange", () => {
      offsetAnchor();
    });
    window.setTimeout(offsetAnchor, 0);

})(jQuery, Drupal);
