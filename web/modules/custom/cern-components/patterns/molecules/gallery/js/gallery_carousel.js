(function ($, Drupal) {

  'use strict';

  $(function () {
    runGalleryCarousel();
    runThumbnailBackground();

    jQuery('.gallery-carousel').each(function () {
      jQuery(this).find(".owl-item").each(function () {
        if (jQuery(this).find('iframe').length > 0) {
          jQuery(this).find('iframe').parent().css('width', '100%');
          jQuery(this).find('iframe').parent().parent().css('width', '100%');
          jQuery(this).find('iframe').parent().parent().parent().css('width', '100%');
          jQuery(this).find('figure.cds-video').next('a').remove();
        }
      });
    });

  });

  // create carousel
  // parameter is for disabling the thumbs for video resource types
  function runGalleryCarousel() {
    let owl = $('.gallery-carousel');
    owl.on('initialized.owl.carousel', function (e) {
      runThumbnailBackground();
      runCDSImages();
      runMaxWidth();
      runOverlay();
    });
    owl.owlCarousel({
      dots: false,
      thumbs: true,
      thumbImage: true,
      nav: false,
      items: 1,
      loop: true,
      animateOut: 'fadeOut',
      mouseDrag: false,
      touchDrag: false
    });
    owl.on('resized.owl.carousel', function (e) {
      runMaxWidth();
    });
  }

  // loading CDS images
  function runCDSImages() {
    // calculate width once cds image are loaded
    $('figure.cds-image img').each(function () {
      if ($(this).prop('complete')) {
        $(this).closest('figure').find('figcaption').css('opacity', '1');
        runMaxWidth();
      }
    });
    $('figure.cds-image img')
      .on('load', function () {
        $(this).closest('figure').find('figcaption').css('opacity', '1');
        runMaxWidth();
      })
      .on('error', function () {
        $(this).prev('span').removeClass('cds-background-hidden');
        $(this).closest('figure').addClass('loading-cds-error');
        let errorurl = $(this).attr('src'); //gets the url which throws the error to find it afterwards in the thumbnail items

        $(this).closest('.owl-carousel').find('.owl-thumb-item').each(function () {
          let background = $(this).attr('style');
          if (background.indexOf(errorurl) >= 0) {
            $(this).addClass('loading-cds-error');
            return false; //returning false to break out of the each() loop as the thumb item was found
          }
        });
      });
  }

  // calculates the max width of the images based on the slide width
  function runMaxWidth() {
    $(".component-gallery .owl-item").each(function () {

      let maxHeight = 0;
      let captionWidth = $(this).find("figure img").width();

      $(this).find("figcaption").css('max-width', captionWidth);

      let imageMaxWidth = $(this).width();
      $(this).find('img').css('max-width', imageMaxWidth - 10);
    })

  }

  //add overlay class
  function runOverlay() {

    if ($('.open-overlay').length > 0) {
      $('.open-overlay').remove();
      runOverlay();
    } else {
      let galleryNumber = 1;
      $('.component-gallery').each(function () {
        $(this).find('.component-gallery__image').each(function () {
          let imageSrcOverlay;
          let linkTitle = "";
          if ($(this).find('figcaption').length > 0) {
            linkTitle = $(this).find('figcaption').text();
            imageSrcOverlay = $(this).siblings(".component-gallery__link").find('a.component-gallery__link__last').attr('href');
            imageSrcOverlay = imageSrcOverlay.split('?')[0] + '?size=medium';
          } else {
            linkTitle = 'no caption';
            imageSrcOverlay = $(this).find("img").attr('src');
          }
          let linkCode = '<a class="open-overlay group' + galleryNumber + '" href="' + imageSrcOverlay + '" title="' + linkTitle + '">open</a>';

          if ($(this).find('.image-gallery-overlay').length > 0) {
            $(this).find('.image-gallery-overlay').append(linkCode);
          } else {
            $(this).find('figure a').append(linkCode);
          }
        });

        $('a.group' + galleryNumber).colorbox({
          rel: "group" + galleryNumber + "",
          transition: "none",
          width: "70%",
          height: "70%",
          scrolling: false,
          photo: true,
          //iframe: true,
          opacity: "0.9",
          onComplete: function () {
            if ($('#cboxTitle').text() === 'no caption') {
              $('#cboxTitle').css('display', 'none');
              $('#cboxLoadedContent').css('height', '100%');
            } else {
              $('#cboxTitle').css('display', 'block');
              $('#cboxLoadedContent').css('height', '77.5%');
            }
            let cboxImageWidth = $('#cboxLoadedContent').find('img').width();
            $('#cboxTitle').css('width', cboxImageWidth);
          },
          onCleanup: function () {
            $(this).closest('.gallery-carousel').trigger('destroy.owl.carousel');

            //the same jQuery as in the start to determine video resources
            runGalleryCarousel(jQuery(this).find('iframe').length === 0);
            runThumbnailBackground();
          }
        });

        galleryNumber = galleryNumber + 1;
      });
    }

  }

  // turn image into background on thumbnails
  function runThumbnailBackground() {
    $(".owl-carousel .owl-thumb-item").each(function () {
      if ($(this).find("img").length > 0) {
        let imageSrc = $(this).find("img").attr('src');
        $(this).css('background', 'url("' + imageSrc + '") no-repeat center center / cover');
        if ($(this).attr('style').length > 0) {
          $(this).find("img").remove();
        }
      }
      let backgroundContent = $(this).css('background-image');
      if (backgroundContent.indexOf('undefined') >= 0) {
        $(this).remove();
      }
    })

  }

})(jQuery, Drupal);
