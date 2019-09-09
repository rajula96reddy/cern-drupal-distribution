(function ($, Drupal) {

  'use strict';

  // adding the neccesary classes on text component with lazy selected 
  jQuery('.is-cern-lazy').each(function () {
    jQuery(this).find('.text-component').addClass('out');
    jQuery(this).find('.text-component p').addClass('fade');
    jQuery(this).find('.text-component h1').addClass('fade');
    jQuery(this).find('.text-component h2').addClass('fade');
    jQuery(this).find('.text-component h3').addClass('fade');
    jQuery(this).find('.text-component h4').addClass('fade');
    jQuery(this).find('.text-component h5').addClass('fade');
    jQuery(this).find('.text-component h6').addClass('fade');
    jQuery(this).find('.text-component figure').addClass('fade');
    jQuery(this).find('.text-component img').addClass('fade');
    jQuery(this).find('.text-component ol').addClass('fade');
    jQuery(this).find('.text-component ul').addClass('fade');
    jQuery(this).find('.text-component table').addClass('fade');
    jQuery(this).find('.text-component blockquote').addClass('fade');
    jQuery(this).find('.text-component hr').addClass('fade');
  });
  /*
  jQuery('main .field--type-text-with-summary').each(function () {
    jQuery(this).addClass('full-text-animation');
  });
  */
  //
  // on pages with ckeditor text
  /*
  jQuery('.full-text-animation').each(function () {
    jQuery(this).addClass('out');
    jQuery(this).find('p').addClass('fade');
    jQuery(this).find('h1').addClass('fade');
    jQuery(this).find('h2').addClass('fade');
    jQuery(this).find('h3').addClass('fade');
    jQuery(this).find('h4').addClass('fade');
    jQuery(this).find('h5').addClass('fade');
    jQuery(this).find('h6').addClass('fade');
    jQuery(this).find('figure').addClass('fade');
    jQuery(this).find('img').addClass('fade');
    jQuery(this).find('ol').addClass('fade');
    jQuery(this).find('ul').addClass('fade');
    jQuery(this).find('table').addClass('fade');
    jQuery(this).find('blockquote').addClass('fade');
    jQuery(this).find('hr').addClass('fade');
  });

  // deleting class for some elements
  jQuery('.panel-group').each(function () {
    jQuery(this).find('p').removeClass('fade');
    jQuery(this).find('h1').removeClass('fade');
    jQuery(this).find('h2').removeClass('fade');
    jQuery(this).find('h3').removeClass('fade');
    jQuery(this).find('h4').removeClass('fade');
    jQuery(this).find('h5').removeClass('fade');
    jQuery(this).find('h6').removeClass('fade');
    jQuery(this).find('figure').removeClass('fade');
    jQuery(this).find('img').removeClass('fade');
    jQuery(this).find('ol').removeClass('fade');
    jQuery(this).find('ul').removeClass('fade');
    jQuery(this).find('table').removeClass('fade');
    jQuery(this).find('blockquote').removeClass('fade');
    jQuery(this).find('hr').removeClass('fade');
  });
  jQuery('.tab-content').each(function () {
    jQuery(this).find('p').removeClass('fade');
    jQuery(this).find('h1').removeClass('fade');
    jQuery(this).find('h2').removeClass('fade');
    jQuery(this).find('h3').removeClass('fade');
    jQuery(this).find('h4').removeClass('fade');
    jQuery(this).find('h5').removeClass('fade');
    jQuery(this).find('h6').removeClass('fade');
    jQuery(this).find('figure').removeClass('fade');
    jQuery(this).find('img').removeClass('fade');
    jQuery(this).find('ol').removeClass('fade');
    jQuery(this).find('ul').removeClass('fade');
    jQuery(this).find('table').removeClass('fade');
    jQuery(this).find('blockquote').removeClass('fade');
    jQuery(this).find('hr').removeClass('fade');
  });
  jQuery('.nav-tabs').each(function () {
    jQuery(this).removeClass('fade');
  });
  jQuery('.tab-pane').each(function () {
    jQuery(this).removeClass('fade');
  });
  jQuery('.navbar-nav').each(function () {
    jQuery(this).removeClass('fade');
  });
*/
  //
  // Get IE or Edge browser version
  var version = detectTextEffectIE();
  var textEffect = true;
  if (version === false) {
    textEffect = true;
  } else if (version >= 12) {
    textEffect = true;
  } else {
    textEffect = false;
  }
  /**
   * detect IE
   * returns version of IE or false, if browser is not Internet Explorer
   */
  function detectTextEffectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
      // IE 10 or older => return version number
      return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }
    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
      // IE 11 => return version number
      var rv = ua.indexOf('rv:');
      return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }
    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
      // Edge (IE 12+) => return version number
      return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }
    // other browser
    return false;
  }

  /*//////////////////////
   *
   * SCROLLMAGIC STARTS
   *
   */ /////////////////////
  // init controller
  var controller = new ScrollMagic.Controller();

  if (textEffect) {
    //fade
    jQuery(".fade").each(function () {
      jQuery(this).addClass("out");
      new ScrollMagic.Scene({
          triggerElement: this,
          triggerHook: 0.65
        })
        .on("enter", function (ev) {
          jQuery(ev.target.triggerElement()).removeClass("out");
        })
        .on("leave", function (ev) {
          jQuery(ev.target.triggerElement()).addClass("out");
        })
        .addTo(controller);
    });

    setTimeout(function () {
      jQuery('.is-cern-lazy').each(function () {
        jQuery(this).find('.text-component').removeClass('out');
      });
      jQuery('.full-text-animation').each(function () {
        jQuery(this).removeClass('out');
      });
    }, 1000);
  } else {
    jQuery('.full-text-animation').each(function () {
      jQuery(this).css('opacity', '1');
    });
    jQuery('.is-cern-lazy .text-component').each(function () {
      jQuery(this).css('opacity', '1');
    });
  }


})(jQuery, Drupal);
