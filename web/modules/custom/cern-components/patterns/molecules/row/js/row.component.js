(function ($, Drupal) {

  'use strict';

  // add offset when using anchor
  function offsetAnchor() {
    if(location.hash.length !== 0) {
      window.scrollTo(window.scrollX, window.scrollY - 130);
    }
  }
  $(window).on("hashchange", () => {
    offsetAnchor();
  });
  window.setTimeout(offsetAnchor, 2000);

  // Get IE or Edge browser version
  var version = detectIE();
  var rowEffectsBrowser = true;
  if (version === false) {
    rowEffectsBrowser = true;
  } else if (version >= 12) {
    rowEffectsBrowser = true;
  } else {
    rowEffectsBrowser = false;
  }
  /**
   * detect IE
   * returns version of IE or false, if browser is not Internet Explorer
   */
  function detectIE() {
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

  // FIX BACKGROUND FIXED FOR IEXPLORER
  // -- REWRITE IEXPLORER SCROLLING
  $(function () {
    if (navigator.userAgent.match(/Trident\/7\./)) {
      document.body.addEventListener("mousewheel", function () {
        event.preventDefault();
        var wd = event.wheelDelta / 2;
        var csp = window.pageYOffset;
        window.scrollTo(0, csp - wd);
      });
    }
  });

  // change cds image to background on background parallax
  if (jQuery('.effect_background_parallax .background-component.background__cds_media').length > 0) {
    jQuery('.effect_background_parallax .background-component.background__cds_media').each(function () {
      var CDSurl = jQuery(this).find('img').attr('src');
      jQuery(this).css('background-image', 'url(' + CDSurl + ')');
      jQuery(this).css('background-size', 'cover');
      jQuery(this).find('figure').css('display', 'none');
      jQuery(this).removeClass('background__cds_media');
      jQuery(this).addClass('background__image');
    });
  }

  if (rowEffectsBrowser) {
    jQuery.fn.isInViewport = function () {
      var elementTop = jQuery(this).offset().top;
      var elementBottom = elementTop + jQuery(this).outerHeight();
      var viewportTop = jQuery(window).scrollTop();
      var viewportBottom = viewportTop + jQuery(window).height();
      return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    jQuery.fn.isTopOnTop = function (offset) {
      if (jQuery('#toolbar-administration').length > 0) {
        offset = offset + 76;
      }
      offset = 0;
      var distance = $(this).offset().top;
      var elementBottom = distance + jQuery(this).outerHeight();
      var finalDistance = elementBottom;
      if ($(this).next('.field--item').length > 0) {
        finalDistance = $(this).next('.field--item').offset().top;
      }
      if (distance <= $(window).scrollTop() + offset && $(window).scrollTop() + offset <= finalDistance) {
        return true;
      }
      return false;
    }

    /*
     * - Is Top of screen on bottom of row?
     */
    jQuery.fn.isTopOnBottom = function () {
      var scrollTop = $(window).scrollTop();
      var viewHeight = jQuery(window).height();
      var distance = $(this).offset().top;
      var elementBottom = distance + jQuery(this).outerHeight();
      var screenBottom = viewHeight + scrollTop;
      if (screenBottom >= distance && elementBottom >= screenBottom) {
        return true;
      }
      return false;
    }

    jQuery.fn.isTopOnTopDownScroll = function () {
      var distance = $(this).offset().top;
      var elementBottom = distance + jQuery(this).outerHeight();
      var finalDistance = elementBottom;
      if ($(this).next('.field--item').length > 0) {
        finalDistance = $(this).next('.field--item').offset().top;
      }
      if (distance >= $(window).scrollTop() + jQuery(window).height()) {
        return true;
      }
      return false;
    }

    jQuery.fn.topPercent = function () {
      var scrollTop = $(window).scrollTop();
      var distance = $(this).offset().top;
      var diffA = scrollTop - distance;
      var elementBottom = distance + jQuery(this).outerHeight();
      var height = $(this).height();
      var diffB = elementBottom - distance;
      return (diffA * 100) / diffB;
    }

    jQuery.fn.bottomPercent = function () {
      var scrollTop = $(window).scrollTop();
      var viewHeight = jQuery(window).height();
      var distance = $(this).offset().top;
      var elementBottom = distance + jQuery(this).outerHeight();
      var height = $(this).height();
      var scrollToBottom = viewHeight + scrollTop;
      var diff = scrollToBottom - distance;

      var percent = 80 - ((distance - scrollTop) * 100) / viewHeight;

      if (percent < -50) {
        return -50;
      } else if (percent > 150) {
        return 150;
      } else {
        return percent;
      }
    }


    // SIMPLE PARALLAX
    function amountscrolled() {
      var winheight = $(window).height()
      var docheight = $(document).height()
      var scrollTop = $(window).scrollTop()
      var trackLength = docheight - winheight
      var pctScrolled = Math.floor(scrollTop / trackLength * 100) // gets percentage scrolled (ie: 80 NaN if tracklength == 0)
      return (scrollTop / trackLength * 100);
    }

    jQuery.fn.rotate = function (degrees) {
      $(this).css({
        '-webkit-transform': 'rotate(' + degrees + 'deg)',
        '-moz-transform': 'rotate(' + degrees + 'deg)',
        '-ms-transform': 'rotate(' + degrees + 'deg)',
        'transform': 'rotate(' + degrees + 'deg)'
      });
      return $(this);
    };

    $(function () {
      // COVER PARALLAX INIT
      jQuery('.layout__region > .field--items > .field--item > .effect_cover_parallax').each(function () {
        $(this).closest('.field--item').addClass('cover-parallax');
      });
      jQuery('.layout__region > .field--items > .field--item:first').each(function () {
        if (jQuery(this).hasClass('cover-parallax')) {
          jQuery(this).find('.background__image').addClass('attach-bg-force');
        }
      });

      // COVER PARALLAX FULL INIT
      jQuery('.layout__region > .field--items > .field--item > .effect_cover_parallax_full').each(function () {
        $(this).closest('.field--item').addClass('cover-parallax-full');
      });

      // LAZY EFFECT INIT
      jQuery('.layout__region > .field--items > .field--item:not(:first)').each(function () {
        if (jQuery(this).find('.component-row').hasClass('is-cern-lazy')) {
          jQuery(this).addClass('cern-lazy-out');
        }
      });
      jQuery('body.has-header .layout__region > .field--items > .field--item:first').removeClass('cern-lazy-out');

      if ($(window).scrollTop() != 0) {
        jQuery("html, body").stop().animate({
          scrollTop: $(window).scrollTop() + 1
        }, 0);
      }

      // BACKGROUND ROTATION EFFECT INIT
      jQuery('.layout__region > .field--items > .field--item > .effect_background_rotation').each(function () {
        var rowHeight = jQuery(this).height();
        jQuery(this).find('.background__image').css('width', rowHeight * 2 + 'px');
        jQuery(this).find('.background__image').css('height', rowHeight * 2 + 'px');
        jQuery(this).find('.background__image').css('position', 'absolute');
        jQuery(this).find('.background__image').css('bottom', 0);
        jQuery(this).find('.background__image').css('left', 'auto');
        jQuery(this).find('.background__image').css('right', -rowHeight);
      });
    });

    // BACKGROUND ROTATION EFFECT INIT
    jQuery(window).on('resize', function (event) {
      jQuery('.layout__region > .field--items > .field--item > .effect_background_rotation').each(function () {
        var rowHeight = jQuery(this).height();
        jQuery(this).find('.background__image').css('width', rowHeight * 2 + 'px');
        jQuery(this).find('.background__image').css('height', rowHeight * 2 + 'px');
        jQuery(this).find('.background__image').css('position', 'absolute');
        jQuery(this).find('.background__image').css('bottom', 0);
        jQuery(this).find('.background__image').css('left', 'auto');
        jQuery(this).find('.background__image').css('right', -rowHeight);
      });
    });

    jQuery(window).on('resize scroll', function (event) {
      event.stopPropagation();
      $('.component-row.effect_background_parallax .background__image').css('background-position-y', amountscrolled() + '%')
      coverParallax();
      if ($(window).width() > 767) {
        bgRotation();
      }
    });

    /**
     * BR ROTATION
     */
    function bgRotation() {
      jQuery('.layout__region > .field--items > .field--item > .effect_background_rotation').each(function () {
        var rowHeight = jQuery(this).height();
        var rowHeightHalf = rowHeight / 2;
        var percent = jQuery(this).bottomPercent();
        if (percent !== false) {
          jQuery(this).find('.background__image').rotate(-(percent * 3.6) / 2);
          jQuery(this).find('.background__image').css('right', 'calc(' + percent + '% - ' + rowHeight + 'px)');
          var opacity = 1 - (percent / 100);
          jQuery(this).find('.background__image').css('opacity', opacity + 0.05);
        }
      });
    }

    /**
     * COVER PARALLAX FULL
     */
    var lastScrollTop = 0;
    window.addEventListener("scroll", function () {
      var st = window.pageYOffset || document.documentElement.scrollTop;
      if (st > lastScrollTop) {
        coverParallaxFullDown();
      } else {
        coverParallaxFullUp();
      }
      lastScrollTop = st;
    }, false);

    /**
     * COVER PARALLAX FULL UP
     */
    function coverParallaxFullUp() {
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full').each(function () {
        if ($(this).next('.field--item').isTopOnTop(0) && !$(this).hasClass('cover-parallax-full-up-in')) {
          $(this).addClass('cover-parallax-full-up-in');
          var height = $(this).height();
          var viewportHeight = $(window).height();
          $(this).next('.field--item').css('margin-top', height + 'px');
          $(this).css('width', '100%');
          $(this).css('top', -(height - viewportHeight) + 'px');
          $(this).css('position', 'fixed');
        }
      });
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full-up-in').each(function () {
        if ($(this).next('.field--item').isTopOnTopDownScroll() && ($(this).hasClass('cover-parallax-full-up-in'))) {
          $(this).removeClass('cover-parallax-full-up-in');
          $(this).removeClass('cover-parallax-full-down-in');
          $(this).css('top', '0');
          $(this).css('position', 'relative');
          $(this).next('.field--item').css('margin-top', '0');
        }
      });
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full-down-in').each(function () {
        if ($(this).next('.field--item').isTopOnTopDownScroll() && ($(this).hasClass('cover-parallax-full-down-in'))) {
          $(this).removeClass('cover-parallax-full-up-in');
          $(this).removeClass('cover-parallax-full-down-in');
          $(this).css('top', '0');
          $(this).css('position', 'relative');
          $(this).next('.field--item').css('margin-top', '0');
        }
      });
    }


    /**
     * COVER PARALLAX FULL DOWN
     */
    function coverParallaxFullDown() {
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full').each(function () {
        if ($(this).next('.field--item').isTopOnBottom()) {
          $(this).addClass('cover-parallax-full-down-in');
          var height = $(this).height();
          var viewportHeight = $(window).height();
          $(this).next('.field--item').css('margin-top', height + 'px');
          $(this).css('width', '100%');
          $(this).css('top', -(height - viewportHeight) + 'px');
          $(this).css('position', 'fixed');
        }
      });
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full-down-in').each(function () {
        if ($(this).next('.field--item').isTopOnTop(0)) {
          $(this).removeClass('cover-parallax-full-down-in');
          $(this).css('top', '0');
          $(this).css('position', 'relative');
          $(this).next('.field--item').css('margin-top', '0');
        }
      });
      jQuery('.layout__region > .field--items > .field--item.cover-parallax-full-up-in').each(function () {
        $(this).addClass('cover-parallax-full-down-in');
        if ($(this).hasClass('cover-parallax-full-up-in') && $(this).hasClass('cover-parallax-full-down-in')) {
          $(this).removeClass('cover-parallax-full-up-in');
          $(this).removeClass('cover-parallax-full-down-in');
          $(this).css('top', '0');
          $(this).css('position', 'relative');
          $(this).next('.field--item').css('margin-top', '0');
        } else {
          var height = $(this).height();
          var viewportHeight = $(window).height();
          $(this).next('.field--item').css('margin-top', height + 'px');
          $(this).css('width', '100%');
          $(this).css('top', -(height - viewportHeight) + 'px');
          $(this).css('position', 'fixed');
        }
      });
    }

    /**
     * COVER PARALLAX
     */
    function coverParallax() {
      jQuery('.layout__region > .field--items > .field--item.cover-parallax').each(function () {
        if ($(this).isTopOnTop(76)) {
          $(this).addClass('on-cover-parallax-in');
          var distance = $(this).offset().top;
          var windowTop = $(window).scrollTop();
          var diff = windowTop - distance;
          var height = $(this).height();
          var percent = diff * 100 / height;
          $(this).find('.component-row .component-row__row').css('opacity', 1 - (diff / height) - 0.2);
          $(this).find('.background__image').addClass('attach-bg');
        } else {
          $(this).removeClass('on-cover-parallax-in');
          $(this).find('.component-row .component-row__row').css('opacity', 1);
          $(this).find('.background__image').removeClass('attach-bg');
        }
        if (jQuery(window).scrollTop() == 0) {
          $(this).find('.component-row .component-row__row').css('opacity', 1);
        }
      });
    }

    // padding top same height that title
    jQuery(function () {
      rowTitle();
    });
    $(window).on('resize', function () {
      rowTitle();
    });

    function rowTitle() {
      $('.component-row').each(function () {
        if ($(this).hasClass('has_title') && $(this).find('.row-component-title').length > 0) {
          var titleHeight = $(this).find('.row-component-title').height();
          var rowPaddingTop = titleHeight + 35;
          $(this).find('.component-row__row').css('padding-top', rowPaddingTop);
        }
      });
    }
  }

})(jQuery, Drupal);
