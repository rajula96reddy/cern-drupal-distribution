(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.CERNComponentsCarouselSlider = {
    attach: function (context) {

      jQuery(function () {
        runCarouselSlider();
        widthCaption();
        bottomCDSCaption();
        mobileImageHeight();
        /* setTimeout(function () {
          widthCaption();
          bottomCDSCaption();
        }, 2000); */
      });

      // jQuery(document)

      /* $(window).on('resize', function () {
        runCarouselSlider();
        widthCaption();
        mobileImageHeight();
        setTimeout(function () {
          widthCaption();
        }, 2000);
      }); */


      jQuery(window).on('load', function (event) {
        widthCaption();
        bottomCDSCaption();
      });      

      // owl carousel
      function runCarouselSlider() {
        var owlSlider = $('.slider-carousel');
        owlSlider.on('initialized.owl.carousel', function (e) {
          $('.owl-item.active video').each(function () {
            $(this).get(0).play();
          });
        });
        owlSlider.owlCarousel({
          nav: true,
          items: 1,
          loop: false,
          dots: false,
          mouseDrag: false
        });
        owlSlider.on('translated.owl.carousel', function (e) {
          $('.owl-item video').each(function () {
            $(this).get(0).pause();
          });
          $('.owl-item.active video').each(function () {
            $(this).get(0).play();
          });
        });
        owlSlider.on('resized.owl.carousel', function (e) {
          widthCaption();
          bottomCDSCaption();
        });

        $(".slider-carousel .owl-prev").html('<span class="icon-arrow-left icons"></span>');
        $(".slider-carousel .owl-next").html('<span class="icon-arrow-right icons"></span>');

        // counter
        $(function () {
          $(".component-slider").each(function () {
              var totalCount = $(this).find('.owl-item').length;
              $(this).find('.owl-item').each(function (k, v) {
                if (!$(this).hasClass("ready-slider-counter")) {
                  $(this).find('.component-slide__caption_image_counter .counter').text(k + 1 + '/' + totalCount);
                  if ($(this).find('figcaption').length > 0) {
                    $(this).find('.component-slide__caption').append("<p>"+$(this).find('figcaption').text()+"</p>");
                  }
                  $(this).addClass("ready-slider-counter")
                }
              });
          });
        });
      }

      // caption width
      function widthCaption() {
        jQuery(".component-slide__image").each(function () {
          if (jQuery(this).find('.component-slide__caption').length != 0) {
            var image_width = jQuery(this).find('img').width();
            jQuery(this).find('.component-slide__caption').css('width', image_width);
          }
          if (jQuery(this).find('figure').length > 0) {
            var image_width = jQuery(this).find('img').width();
            jQuery(this).find('figcaption').css('width', image_width);
          }
        });
        jQuery(".component-slide__video").each(function () {
          if (jQuery(this).find('.component-slide__caption').length != 0) {
            var video_width = jQuery(this).find('video').width();
            jQuery(this).find('.component-slide__caption').css('width', video_width);
          }
        });

      }

      function bottomCDSCaption() {
        jQuery(".component-slide__image").each(function () {
          if (jQuery(this).find('figure').length > 0) {
            jQuery(this).find('figcaption').css('bottom', jQuery(this).find('figcaption').outerHeight(true)+'px');
            jQuery(this).find('figure').css('margin-bottom', '-'+jQuery(this).find('figcaption').outerHeight(true)+'px');
          }
        });
      }

      // image size on mobile (< 768px)
      function mobileImageHeight() {
        if ($(window).width() < 767) {

          $('.owl-stage').each(function () {
            // calculate the bigger caption
            var maxHeight = 0;
            $(this).find('.component-slide__caption').each(function () {
              var thisH = $(this).height();
              if (thisH > maxHeight) {
                maxHeight = thisH;
              }
            });
            // calculate the height to sustract to the viewport
            var minus = maxHeight + 100;
            // set image max-height
            $(this).find(".component-slide__image").each(function () {
              $(this).find('img').css('max-height', 'calc(100vh - ' + minus + 'px)');
              var imageNow = $(this).find('img').height();
              if (imageNow < 200) {
                $(this).find('img').css('max-height', '200px');
              }
            });
            // set video max-height
            $(this).find(".component-slide__video").each(function () {
              $(this).find('video').css('max-height', 'calc(100vh - ' + minus + 'px)');
              var videoNow = $(this).find('video').height();
              if (videoNow < 200) {
                $(this).find('video').css('max-height', '200px');
              }
            });
          })


        }
      }
    }
  };

})(jQuery, Drupal);
