(function ($, Drupal) {

  'use strict';

  // language switcher

  $(document).ready(function () {

    if ($('header .block-language').length > 0) {
      $('header .block-language ul.links li').each(function () {
        if ($(this).hasClass('is-active')) {
          $(this).css('display', 'none');
          var activeLanguage = $(this).find('a').text();
          $('header .block-language').append('<div class="active-language"><a href="javascript:;"><span class="separator">|</span>' + activeLanguage + '<span class="caret"></span></a></div>');
        }
      });
    }

    if ($('header .active-language').length > 0) {
      $('header .active-language a').click(function () {
        $('header .block-language > ul.links').toggleClass('language-expanded');
      })
    }

    $(window).click(function () {
      // $('#cern-toolbar').removeClass('signin-expand');
      $('header .block-language > ul.links').removeClass('language-expanded');
    }); 

    $('header .active-language a').click(function (event) {
      event.stopPropagation();
    }); 

  });

})(jQuery, Drupal);
