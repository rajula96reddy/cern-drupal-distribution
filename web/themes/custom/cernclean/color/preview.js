/**
 * @file
 * Preview for the Bartik theme.
 */
(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.color = {
    logoChanged: false,
    callback: function (context, settings, $form) {
      // Change the logo to be the real one.
      if (!this.logoChanged) {
        if ($('#edit-logo-path--description').find('code').length > 0) {
          $('#edit-logo-path--description').find('code').each(function () {
            var newLogo = $(this).html();
            if (/sites/i.test(newLogo)) {
              $('.color-preview .color-preview-logo img').attr('src', '/' + newLogo);
            }
          });
        }
        if ($('#edit-default-logo').is(':checked')) {
          $('.color-preview .color-preview-logo img').attr('src', drupalSettings.color.logo);
        }
        this.logoChanged = true;
      }
      // Remove the logo if the setting is toggled off.
      if (drupalSettings.color.logo === null) {
        $('div').remove('.color-preview-logo');
      }

      var $colorPreview = $form.find('.color-preview');
      var $colorPalette = $form.find('.js-color-palette');

      // Header.
      var $colorPreviewHeader = $colorPreview.find('.color-preview-header');
      $colorPreviewHeader.css('background', $colorPalette.find('input[name="palette[header-background]"]').val());
      $colorPreviewHeader.find('a').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[menu-link]"]').val());
      });
      $colorPreviewHeader.find('.color-preview-sitename').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[header-name]"]').val());
      });
      $colorPreviewHeader.find('a span.animated-line').each(function () {
        $(this).css('background', $colorPalette.find('input[name="palette[icons-chevrons-underline]"]').val());
      });

      var $colorPreviewContent = $colorPreview.find('.color-preview-main');

      // Background.
      $colorPreviewContent.css('background', $colorPalette.find('input[name="palette[body-bg]"]').val());

      // text + title
      $colorPreviewContent.find('.color-preview-node .preview-content').css('color', $colorPalette.find('input[name="palette[text-color]"]').val());
      $colorPreviewContent.find('.color-preview-page-title').css('color', $colorPalette.find('input[name="palette[text-color]"]').val());

      // li bullets
      $colorPreviewContent.find('.color-preview-node .preview-content .bullet').css('background', $colorPalette.find('input[name="palette[html-u-underline]"]').val());

      // ul underline color
      $colorPreviewContent.find('.color-preview-node .preview-content u').css('text-decoration-color', $colorPalette.find('input[name="palette[html-u-underline]"]').val());

      // caption
      $colorPreviewContent.find('.component-slide__caption').css('color', $colorPalette.find('input[name="palette[html-caption]"]').val());

      // hr
      $colorPreviewContent.find('hr').css('border-top-color', $colorPalette.find('input[name="palette[html-hr]"]').val());

      // link
      $colorPreviewContent.find('.color-preview-node .preview-content a').css('color', $colorPalette.find('input[name="palette[link-color]"]').val());
      $colorPreviewContent.find('.color-preview-node .preview-content a:hover').css('color', $colorPalette.find('input[name="palette[link-color-hover]"]').val());
      $colorPreviewContent.find('.color-preview-node .preview-content .a-demo a').hover(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[link-color-hover]"]').val());
      }, function () {
        $(this).css('color', $colorPalette.find('input[name="palette[link-color]"]').val());
      });

      // tabs
      $colorPreviewContent.find('.nav-tabs > li > a').css('background', $colorPalette.find('input[name="palette[tabs-inactive-background]"]').val());
      $colorPreviewContent.find('.nav-tabs > li > a').css('color', $colorPalette.find('input[name="palette[tabs-inactive-text]"]').val());
      $colorPreviewContent.find('.nav-tabs > li.active > a').css('background', $colorPalette.find('input[name="palette[tabs-active-background]"]').val());
      $colorPreviewContent.find('.nav-tabs > li.active > a').css('color', $colorPalette.find('input[name="palette[tabs-active-text]"]').val());
      $colorPreviewContent.find('.tab-content').css('background', $colorPalette.find('input[name="palette[tabs-active-background]"]').val());
      $colorPreviewContent.find('.tab-content').css('color', $colorPalette.find('input[name="palette[tabs-active-text]"]').val());

      // table
      $colorPreviewContent.find('table').css('background', $colorPalette.find('input[name="palette[table-background]"]').val());
      $colorPreviewContent.find('table thead tr th').css('background', $colorPalette.find('input[name="palette[table-header-background]"]').val());
      $colorPreviewContent.find('table thead tr th').css('color', $colorPalette.find('input[name="palette[table-header-color]"]').val());
      $colorPreviewContent.find('table tbody tr td').css('color', $colorPalette.find('input[name="palette[table-row-text]"]').val());
      $colorPreviewContent.find('table tbody tr:nth-child(2n+1)').css('background', $colorPalette.find('input[name="palette[table-row-even]"]').val());
      $colorPreviewContent.find('table tbody tr:nth-child(2n+2)').css('background', $colorPalette.find('input[name="palette[table-row-odd]"]').val());
      $colorPreviewContent.find('table tfoot tr td').css('background', $colorPalette.find('input[name="palette[table-footer-background]"]').val());
      $colorPreviewContent.find('table tfoot tr td').css('color', $colorPalette.find('input[name="palette[table-footer-text]"]').val());

      // sliders dots
      $colorPreviewContent.find('.owl-dots').each(function () {
        $(this).find('.owl-dot').css('background', $colorPalette.find('input[name="palette[icons-slider-navigation-inactive]"]').val());
        $(this).find('span').css('border-color', $colorPalette.find('input[name="palette[icons-slider-navigation-active]"]').val());
        $(this).find('.owl-dot.active').css('background', $colorPalette.find('input[name="palette[icons-slider-navigation-active]"]').val());
      });

      // blockquote
      $colorPreviewContent.find('blockquote').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[icons-blockquotes]"]').val());
      });

      // slider arrows
      $colorPreviewContent.find('.component-slider .owl-nav').each(function () {
        $(this).find('.owl-prev').css('color', $colorPalette.find('input[name="palette[icons-slider-arrow-active]"]').val());
        $(this).find('.owl-next').css('color', $colorPalette.find('input[name="palette[icons-slider-arrow-inactive]"]').val());
      });

      // tags
      $colorPreviewContent.find('.field--type-entity-reference .field--item').each(function () {
        $(this).find('a').css('background', $colorPalette.find('input[name="palette[tag-background]"]').val());
        $(this).find('a').css('color', $colorPalette.find('input[name="palette[tag-color]"]').val());
      });

      // buttons
      $colorPreviewContent.find('.buttons-wrapper').each(function () {
        $(this).find('.button').css('border-color', $colorPalette.find('input[name="palette[buttons-border]"]').val());
        $(this).find('.btn-primary').css('background', $colorPalette.find('input[name="palette[buttons-primary-background]"]').val());
        $(this).find('.btn-primary').css('color', $colorPalette.find('input[name="palette[buttons-primary-text]"]').val());
        $(this).find('.btn-default').css('background', $colorPalette.find('input[name="palette[buttons-secondary-background]"]').val());
        $(this).find('.btn-default').css('color', $colorPalette.find('input[name="palette[buttons-secondary-text]"]').val());
      });
      $colorPreviewContent.find('.btn-primary').hover(function () {
        $(this).css('background', $colorPalette.find('input[name="palette[buttons-primary-background-hover]"]').val());
        $(this).css('color', $colorPalette.find('input[name="palette[buttons-primary-text-hover]"]').val());
      }, function () {
        $(this).css('background', $colorPalette.find('input[name="palette[buttons-primary-background]"]').val());
        $(this).css('color', $colorPalette.find('input[name="palette[buttons-primary-text]"]').val());
      });
      $colorPreviewContent.find('.btn-default').hover(function () {
        $(this).css('background', $colorPalette.find('input[name="palette[buttons-secondary-background-hover]"]').val());
        $(this).css('color', $colorPalette.find('input[name="palette[buttons-secondary-text-hover]"]').val());
      }, function () {
        $(this).css('background', $colorPalette.find('input[name="palette[buttons-secondary-background]"]').val());
        $(this).css('color', $colorPalette.find('input[name="palette[buttons-secondary-text]"]').val());
      });

      // views
      $colorPreviewContent.find('.views-col article').each(function () {
        $(this).css('background', $colorPalette.find('input[name="palette[views-cards-background]"]').val());
        $(this).css('color', $colorPalette.find('input[name="palette[views-cards-color]"]').val());
        $(this).find('a').css('color', $colorPalette.find('input[name="palette[views-cards-color]"]').val());
        $(this).find('a span').css('color', $colorPalette.find('input[name="palette[icons-chevrons-underline]"]').val());
        $(this).find('h2 a').css('color', $colorPalette.find('input[name="palette[views-cards-title]"]').val());
      });

      // Footer.
      var $colorPreviewFooter = $colorPreview.find('.color-preview-footer-wrapper');
      $colorPreviewFooter.css('background', $colorPalette.find('input[name="palette[footer-background]"]').val());
      $colorPreviewFooter.css('color', $colorPalette.find('input[name="palette[footer-text]"]').val());
      $colorPreviewFooter.find('h2').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[footer-text]"]').val());
      });
      $colorPreviewFooter.find('a').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[footer-link]"]').val());
      });
      $colorPreviewFooter.find('.followus a').hover(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[footer-link-hover]"]').val());
      }, function () {
        $(this).css('color', $colorPalette.find('input[name="palette[footer-link]"]').val());
      });
      $colorPreviewFooter.find('a span.marker').each(function () {
        $(this).css('color', $colorPalette.find('input[name="palette[icons-chevrons-underline]"]').val());
      });
      $colorPreviewFooter.find('a span.animated-line').each(function () {
        $(this).css('background', $colorPalette.find('input[name="palette[icons-chevrons-underline]"]').val());
      });

      // show color wheel
      $('.js-color-palette .js-form-item input.form-text').each(function () {
        $(this).focus(function () {
          var position = $(this).position().top;
          $('.color-placeholder').animate({
            top: position + 10,
          }, 200, "linear");
          $('.color-placeholder').fadeIn();
        });
        $(this).blur(function () {
          $('.color-placeholder').fadeOut();
        });
      })

    }
  };
})(jQuery, Drupal, drupalSettings);
