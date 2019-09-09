(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.CERNComponentsCarouselHeader = {
    attach: function (context) {

      // Get IE or Edge browser version
      var version = detectIE2();
      var isIEDownButton = false;
      if (version === false) {
        isIEDownButton = true;
      } else if (version >= 12) {
        isIEDownButton = false;
      } else {
        isIEDownButton = true;
      }
      /**
       * detect IE
       * returns version of IE or false, if browser is not Internet Explorer
       */
      function detectIE2() {
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
        var mac = ua.indexOf('Mac OS');
        if (mac > 0) {
          // IE 10 or older => return version number
          return false;
        }
        // other browser
        return false;
      }

      runCarouselHeader();

      jQuery(function () {
        fixHalfheight();
      });

      var resizeId;
      $(window).resize(function () {
        clearTimeout(resizeId);
        resizeId = setTimeout(doneResizing, 500);
      });

      function doneResizing() {
        runCarouselHeader();
        fixHalfheight();
      }


      /* $(window).on('resize', function () {
        runCarouselHeader();
        fixHalfheight();
      }); */
      jQuery(window).on("orientationchange", function (event) {
        fixHalfheight();
      });

      function runCarouselHeader() {
        var $owl = $(".header-carousel", context).owlCarousel({
          nav: false,
          items: 1,
          loop: true,
          dots: true,
          mouseDrag: false,
          touchDrag: false
        });
        var first_slide_time = drupalSettings.cern_hero_header.slide_time[0];
        if (first_slide_time > 0) {
          $('.owl-carousel').data('owl.carousel').settings.autoplayTimeout = first_slide_time;
          $owl.trigger('play.owl.autoplay');
        }

        $owl.on('changed.owl.carousel', function (event) {
          var currentSlide = event.page.index;
          var $video = $(event.currentTarget).find('.owl-item:not(.cloned)').eq(currentSlide).find('video');
          if ($video.length > 0) {
            var video = $video.get(0);
            video.currentTime = 0;
            //video.pause();
            var isPlaying = video.currentTime > 0 && !video.paused && !video.ended &&
              video.readyState > 2;
            if (!isPlaying) {
              video.play();
            }
          }
          $owl.trigger('stop.owl.autoplay', [event.page.index]);
        });

        // hidden the inactive slides on moves ending
        $owl.on('translated.owl.carousel', function (event) {
          $('.owl-item:not(.active)').each(function () {
            $(this).css('opacity', '0');
          });
        });

        // show all the items before moves
        $owl.on('translate.owl.carousel', function (event) {
          $('.owl-item').each(function () {
            $(this).css('opacity', '1');
          });
          $('.cern-component-header-blocks .owl-dots .owl-dot').each(function () {
            $(this).css('cursor', 'pointer');
          });
          $('.cern-component-header-blocks .owl-dots .owl-dot.active').each(function () {
            $(this).css('cursor', 'default');
          });
        });

        $owl.on('stop.owl.autoplay', function (event, slide_index) {
          var slide_time = drupalSettings.cern_hero_header.slide_time[slide_index];
          $('.owl-carousel').data('owl.carousel').settings.autoplayTimeout = slide_time;
          $owl.trigger('play.owl.autoplay');
        });

        // Down button of carrousel go to anchor
        var ua = navigator.userAgent,
          event = (ua.match(/iPad/i)) ? "touchstart" : "click";

        $(".component-header").off("touchstart click").on("touchstart click", ".component-header__scroll", function (e) {

          var gotoDownbtn = $(this).closest('.component-row').offset().top + $(this).closest('.component-row').height() - 70;
          if ($('body').hasClass('toolbar-fixed')) {
            gotoDownbtn -= 40;
          }
          if ($('body').hasClass('toolbar-horizontal') && $('body').hasClass('toolbar-tray-open')) {
            gotoDownbtn -= 40;
          }

          jQuery("html, body").stop().animate({
            scrollTop: gotoDownbtn + 5
          }, 400, 'swing');
        });

        /*function scrollTo(element, to, duration) {
          var start = document.documentElement.scrollTop,
            change = to - start,
            currentTime = 0,
            increment = 20;

          var animateScroll = function () {
            currentTime += increment;
            var val = Math.easeInOutQuad(currentTime, start, change, duration);
            document.documentElement.scrollTop = val;
            if (currentTime < duration) {
              setTimeout(animateScroll, increment);
            }
          };
          animateScroll();
        }

        function scrollTo2(element, to, duration) {
          var start = document.body.scrollTop,
            change = to - start,
            currentTime = 0,
            increment = 20;

          var animateScroll = function () {
            currentTime += increment;
            var val = Math.easeInOutQuad(currentTime, start, change, duration);
            document.body.scrollTop = val;
            if (currentTime < duration) {
              setTimeout(animateScroll, increment);
            }
          };
          animateScroll();
        }*/

        //t = current time
        //b = start value
        //c = change in value
        //d = duration
        Math.easeInOutQuad = function (t, b, c, d) {
          t /= d / 2;
          if (t < 1) return c / 2 * t * t + b;
          t--;
          return -c / 2 * (t * (t - 2) - 1) + b;
        };

        // Fix with of carrousel items in full screen
        window.onload = function () {
          var video = $(".owl-item.active video").get(0);
          if (video !== undefined) {
            var isPlaying = video.currentTime > 0 && !video.paused && !video.ended &&
              video.readyState > 2;
            if (!isPlaying) {
              video.play();
            }
          }
        }
      }

      function fixHalfheight() {
        jQuery(".header-carousel").each(function () {
          var $owl = jQuery(this);
          if ($owl.closest('.component-row').hasClass('is_half_height')) {
            var owlMinHeight = 0;
            var theItem = null;
            $owl.find('.owl-item').each(function () {
              if (jQuery(this).outerHeight(true) > owlMinHeight) {
                owlMinHeight = jQuery(this).find('.header-block__title').outerHeight(true);
                theItem = jQuery(this);
                jQuery(this).closest('.cern-component-header-blocks').css('height', (Math.round(owlMinHeight) + 25) + 'px');
                jQuery(this).closest('.is_half_height').css('height', (Math.round(owlMinHeight) + 25) + 'px');
              }
            });
          }
          // if ($owl.closest('.field--item')) 
          if ($owl.closest('.field--item').index() == 0) {
            //jQuery("nav.tabs").hide();
          }
          setTimeout(function () {
            if ($owl.closest('.field--item').index() == 0) {
              if (theItem) {
                jQuery("nav.tabs").css("top", (theItem.closest('.field--item').next('.field--item').position().top - 15) + "px");
              }
              jQuery("nav.tabs").show();
            }
          }, 1000);
        });
      }

      /*
               var $owl = jQuery(this);
          if ($owl.closest('.component-row').hasClass('is_half_height')) {
            var owlMinHeight = 0;
            var theItem = null;
            $owl.find('.owl-item').each(function(){
              if (jQuery(this).outerHeight(true) > owlMinHeight) {
                owlMinHeight = jQuery(this).find('.header-block__title').outerHeight(true);
                theItem = jQuery(this);
                jQuery(this).closest('.cern-component-header-blocks').css('height', (Math.round(owlMinHeight) + 25) + 'px');
                jQuery(this).closest('.is_half_height').css('height', (Math.round(owlMinHeight) + 25) + 'px');
              }
            }); 
            //theItem.closest('.cern-component-header-blocks').css('height', (Math.round(owlMinHeight) + 25) + 'px');
            //theItem.closest('.is_half_height').css('height', (Math.round(owlMinHeight) + 25) + 'px');
            if($owl.closest('.field--item').index() == 0) {
              jQuery("nav.tabs").css("top", theItem.closest('.field--item').next('.field--item').top()+"px");
            }
          }
      */
      /* jQuery(function(){}) */

      if ($('.cern-component-header-blocks .owl-dots').length > 0) {
        $('.cern-component-header-blocks .owl-dots .owl-dot').each(function () {
          $(this).css('cursor', 'pointer');
        });
        $('.cern-component-header-blocks .owl-dots .owl-dot.active').each(function () {
          $(this).css('cursor', 'default');
        });
      }

    }
  };

})(jQuery, Drupal, drupalSettings);
