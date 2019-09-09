(function ($, Drupal) {

  'use strict';

  $(function () {
    var bodyH = $("body").height();
    var windowH = $(window).height();

    // $("#cern-global-loading-layout").hide();

    if (jQuery('#cern-toolbar').length > 0) {
      jQuery('body').addClass('cern-toolbar');
    }

    //sticky header
    if (bodyH > windowH) {
      var diffH = bodyH - windowH;
      if (diffH > 85) {
        if ($(window).scrollTop() > 1) {
          $('body').addClass("sticky-header");
          //animatePaddingTop("body.sticky-header main", 45);
        } else {
          $('body').removeClass("sticky-header");
          //animatePaddingTop("body.sticky-header main", 0);
        }
      }

      if (diffH < 130) {
        $('body').addClass("not-enougth-scroll");
      }
      /* $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
          $('body').addClass("sticky-header");
        } else {
          $('body').removeClass("sticky-header");
        }
      }); */
      /* } else {
        $(window).scroll(function () {
          jQuery('body').addClass("nosticky-header");
          if (jQuery(window).scrollTop() > 40) {
            jQuery('body').addClass("nosticky-header-hide-toolbar");
          } else {
            jQuery('body').removeClass("nosticky-header-hide-toolbar");
          }
        });
      } */
    }

    // has header
    if ($('.component-row__has-header').length > 0) {
      $('body').addClass('has-header');
    }

    // MAIN MENU active father
    if (jQuery('nav.main-menu .dropdown-menu').length > 0) {
      jQuery('nav.main-menu .dropdown-menu li a.is-active').each(function () {
        jQuery(this).closest('li.dropdown').find('>a').addClass('is-active');
      });
    }

  });


  //sticky header
  $(window).scroll(function () {
    var bodyH = $("body").height();
    var windowH = $(window).height();
    if (bodyH > windowH) {
      var diffH = bodyH - windowH;

      if (diffH > 85) {
        // $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
          $('body').addClass("sticky-header"); //toolbar-fixed
          //animatePaddingTop("body.sticky-header main", 45);
          $('body').addClass("toolbar-fixed");
        } else {
          $('body').removeClass("sticky-header");
          //animatePaddingTop("body.sticky-header main", 0);
          //$('body').removeClass("toolbar-fixed");
        }
        //});
      } else {
        //$(window).scroll(function () {
        jQuery('body').addClass("nosticky-header");
        if (jQuery(window).scrollTop() > 40) {
          jQuery('body').addClass("nosticky-header-hide-toolbar");
        } else {
          jQuery('body').removeClass("nosticky-header-hide-toolbar");
          jQuery('body').removeClass("sticky-header");
          //animatePaddingTop("body.sticky-header main", 0);
        }
        // });
      }
    }
  });

  /* function animatePaddingTop(item, amount) {
    $(item).stop().animate({ 
      'padding-top' : amount
    }, 300);
  } */

  /*   jQuery(window).scroll(function () {
      if (jQuery("body").hasClass("toolbar-fixed")) {
          jQuery("#toolbar-administration").css("top", "0");
      }
    }); */

  // ckeditor classes
  if ($('.img-left').length > 0) {
    $('.img-left').each(function () {
      $(this).next().addClass("img-left");
      $(this).remove();
    });
  }
  if ($('.img-right').length > 0) {
    $('.img-right').each(function () {
      $(this).next().addClass("img-right");
      $(this).remove();
    });
  }
  if ($('.img-clear').length > 0) {
    $('.img-clear').each(function () {
      $(this).next().addClass("img-clear");
      $(this).remove();
    });
  }
  if ($('.img-clear-right').length > 0) {
    $('.img-clear-right').each(function () {
      $(this).before('<div class="div-clear-right"></div>');
      $(this).next().addClass("img-clear-right");
      $(this).next().after('<div class="div-clear-right"></div>');
      $(this).remove();
    });
  }
  if ($('.img-clear-center').length > 0) {
    $('.img-clear-center').each(function () {
      $(this).next().addClass("img-clear-center");
      $(this).remove();
    });
  }

  // textarea animation
  if ($('textarea.form-control').length > 0) {
    var textareaHeight = $('textarea.form-control').height();
    $('textarea.form-control').each(function () {
      $('textarea.form-control').css('height', '35px');
    });
    $('textarea.form-control').focus(function () {
      $(this).animate({
        height: textareaHeight
      }, 400);
    });
    $('textarea.form-control').blur(function () {
      $(this).animate({
        height: 35
      }, 400);
    });
  }

  // language dropdown
  if ($('#language').length > 0) {}

  // Slider auto-height FIX 
  // - The slider do not initialize with the height of his first slide 
  $(function () {
    setTimeout(function () {
      var firstItem = $('.owl-stage-outer.owl-height .owl-item.active');
      $('.owl-stage-outer.owl-height').css('height', firstItem.height());
    }, 1000);
  });

  // Header menu mobile background fill on expand
  $('header').on('click', '.navbar-header button.navbar-toggle', function () {
    if ($(this).hasClass('collapsed')) {
      $('header').addClass('menu-expanded');
    } else {
      $('header').removeClass('menu-expanded');
    }
  });

  // cds image loading
  if ($('img[src*="cds.cern.ch"]').length > 0) {
    $.each($('img[src*="cds.cern.ch"]'), function () {
      $(this).parent().prepend('<span class="cds-background" style="min-height: 250px; width: 100%; display: block;position: relative;z-index: 0;height: 100%;"><span><span></span></span><span></span></span>');
      $(this).closest('figure').css('height', '100%');
      $(this).closest('figure').css('position', 'relative');
      $(this).closest('figure').addClass('loading-cds');
      $(this).css('position', 'relative');
      $(this).css('top', '-250px');
      $(this).css('margin-bottom', '-250px');
      $(this).css('width', 'auto');
    });
  }
  $(function () {
    jQuery('figure.cds-image img').each(function () {
      if (jQuery(this).prop('complete')) {
        jQuery(this).prev('span').addClass('cds-background-hidden');
        jQuery(this).closest('figure').removeClass('loading-cds');
      }
    });

    $.each($('img[src*="cds.cern.ch"]'), function () {
      if (jQuery(this).prop('complete')) {
        jQuery(this).prev('span.cds-background').addClass('cds-background-hidden');
        jQuery(this).parent().removeClass('loading-cds');
      }
    })
  });
  jQuery('figure.cds-image img')
    .on('load', function () {
      jQuery(this).prev('span').addClass('cds-background-hidden');
      jQuery(this).prev('span').find('.cds-img-error').hide();
      jQuery(this).closest('figure').removeClass('loading-cds');
      jQuery(this).closest('figure').removeClass('loading-cds-error');
    })
    .on('error', function () {
      jQuery(this).prev('span').removeClass('cds-background-hidden');
      jQuery(this).closest('figure').addClass('loading-cds-error');
    });


  jQuery('img[src*="cds.cern.ch"]')
    .on('load', function () {
      jQuery(this).prev('span.cds-background').addClass('cds-background-hidden');
      jQuery(this).prev('span').find('.cds-img-error').hide();
      jQuery(this).parent().removeClass('loading-cds');
      jQuery(this).parent().removeClass('loading-cds-error');
    })
    .on('error', function () {
      jQuery(this).prev('span.cds-background').removeClass('cds-background-hidden');
      jQuery(this).parent().addClass('loading-cds-error');
    });


  // accordion open trail
  if (jQuery('.accordion-cern').length > 0) {
    jQuery('.accordion-cern').each(function () {
      jQuery(this).find('.panel-collapse.collapse.in').parent('.panel').find('.panel-heading a').toggleClass('is-open');

      jQuery(this).find('.panel-heading a').click(function () {
        jQuery('.accordion-cern').find('.panel-heading a').removeClass('is-open');
        jQuery(this).addClass('is-open');
      })
    });
    jQuery('.accordion-cern .panel-heading a').click(function () {

      if (jQuery(this).closest('.panel').find('.panel-collapse').hasClass('in')) {
        jQuery(this).removeClass('is-open');
      }

    });

  }

  // ckeditor cern icon
  if (jQuery('.cern_full_html').length > 0) {
    jQuery('.cern_full_html span').each(function () {
      if (jQuery(this).css('font-family') == 'CernIcons, CernIcons, cern-icons') {
        jQuery(this).closest('a').addClass('linked-cern-icons');
      }
    });
  }

  // check if CERN tollbar is ON and put a class on the body
  jQuery(window).on('load', function (event) {
    if (jQuery('#cern-toolbar').length > 0) {
      jQuery('body').addClass('cern-toolbar');
    }
  });

  // main menu responsive menu scrolling
  if (jQuery('.main-menu').length > 0) {
    jQuery('.main-menu').each(function () {
      jQuery(this).find('.navbar-toggle').click(function () {
        var menuExpanded = jQuery(this).attr('aria-expanded');
        if (menuExpanded == 'false') {
          jQuery('body').css('overflow', 'hidden');
        }
        if (menuExpanded == 'true') {
          jQuery('body').css('overflow', 'initial');
        }
      });
    });
  }

  // check if ADMIN tollbar is ON
  if (jQuery('#toolbar-bar').length > 0) {

    jQuery('.toolbar-item').each(function () {
      var ToolbarItem = $(this).text().toLowerCase();
      $(this).addClass(ToolbarItem);
    })

  }

  // tabs with has-header + is_half_height
  jQuery(function () {
    if (jQuery('.has-header .layout__region--content .field--items .field--item:first-child .is_half_height').length > 0) {
      var headerHeight = jQuery('.has-header .layout__region--content .field--items .field--item:first-child .is_half_height .cern-component-header-blocks.component-header').height();
      var headerHeight = headerHeight - 25;
      jQuery('.region-content>.tabs').css('top', headerHeight);
    }
  });


  // 
  jQuery(function () {
    marginForSocialIcons();
  });
  jQuery(window).resize(function () {
    marginForSocialIcons();
  });

  function marginForSocialIcons() {
    if (jQuery(window).width() >= 1072) {
      if (jQuery('body:not(.page-node-type-story-page):not(.page-node-type-faq-page):not(.page-node-type-landing-page) .block-social-sharing-block').length > 0) {
        jQuery('main').css('padding-left', '4%');
      }
    } else {
      if (jQuery('body:not(.page-node-type-story-page):not(.page-node-type-faq-page):not(.page-node-type-landing-page) .block-social-sharing-block').length > 0) {
        jQuery('main').css('padding-left', '0');
      }
    }
  }


  jQuery(function () {
    jQuery(".component-preview-cards .preview-card__author a").each(function () {
      jQuery(this).removeAttr('href');
    });
  });

  // cern toolbar without  admin toolbar
  if ($('#toolbar-administration').length > 0) {} else {
    $('#cern-toolbar').css('top', '0');
    $('#cern-toolbar').css('opacity', '1');
    $('body').addClass('no-admin-toolbar');
  }

  // header without cern toolbar AND without  admin toolbar
  if ($('#toolbar-administration').length == 0 && $('#cern-toolbar').length == 0) {
    $('header').css('top', '0');
  }

  // //LAZY EFFECT
  // jQuery(function () {
  //   runLazy();
  //   runLazyContent();
  // });

  // // lazy effect on resize o scroll
  // jQuery(window).on('resize scroll', function (event) {
  //   runLazy();
  //   runLazyContent();
  // });

  // jQuery.fn.isInViewportLazy = function () {
  //   var elementTop = jQuery(this).offset().top;
  //   var elementBottom = elementTop + jQuery(this).outerHeight();
  //   var viewportTop = jQuery(window).scrollTop();
  //   var viewportBottom = viewportTop + jQuery(window).height();
  //   return elementBottom > viewportTop && elementTop < viewportBottom;
  // };

  // function runLazy() {
  //   jQuery('.layout__region > .field--items > .field--item').each(function () {
  //     if (jQuery(this).isInViewportLazy()) {
  //       if (jQuery(this).hasClass('cern-lazy-out')) {
  //         jQuery(this).removeClass('cern-lazy-out');
  //         jQuery(this).addClass('cern-lazy-in cern-lazy-inviewport');
  //       }
  //     }
  //   });
  // }

  // viewport info + headers info
  // jQuery.fn.isInContentViewportLazy = function () {
  //   // height of all toolbars
  //   var headerBars = 0;
  //   var cernToolbar = 0;
  //   var adminMenu = 0;
  //   var adminSubMenu = 0;
  //   var stickyHeader = 0;
  //   if (jQuery('#cern-toolbar').length) {
  //     var cernToolbar = jQuery('#cern-toolbar').height();
  //   }
  //   if (jQuery('nav#toolbar-bar').length) {
  //     var adminMenu = jQuery('nav#toolbar-bar').height();
  //   }
  //   if (jQuery('.toolbar-tray.is-active.toolbar-tray-horizontal').length) {
  //     var adminSubMenu = jQuery('.toolbar-tray.is-active.toolbar-tray-horizontal').height();
  //   }
  //   if (jQuery('.sticky-header header .header-wrapper').length) {
  //     var stickyHeader = jQuery('.sticky-header header .header-wrapper').height();
  //   }
  //   var headerBars = Math.abs(cernToolbar + adminMenu + adminSubMenu + stickyHeader);

  //   var elementTop = jQuery(this).offset().top;
  //   var elementBottom = elementTop + jQuery(this).outerHeight();
  //   var viewportTop = jQuery(window).scrollTop();
  //   var viewportTop = viewportTop + headerBars;
  //   var viewportBottom = viewportTop + jQuery(window).height();
  //   return elementBottom > viewportTop && elementTop < viewportBottom;
  // };

  // lazy effect on content
  // function runLazyContent() {
  //   jQuery('.cern-lazy-in').addClass('lazy-first-charge');
  //   jQuery('.cern-lazy-in p, .cern-lazy-in h1, .cern-lazy-in h2, .cern-lazy-in h3, .cern-lazy-in h4, .component-row.is-cern-lazy p, .component-row.is-cern-lazy h1, .component-row.is-cern-lazy h2, .component-row.is-cern-lazy h3, .component-row.is-cern-lazy h4, .component-row.is-cern-lazy figure').each(function () {
  //     if (jQuery(this).isInContentViewportLazy()) {
  //       jQuery(this).addClass('cern-lazy-in-content');
  //       jQuery(this).removeClass('cern-lazy-out-content');
  //     } else {
  //       jQuery(this).addClass('cern-lazy-out-content');
  //       jQuery(this).removeClass('cern-lazy-in-content');
  //     }
  //   });
  //   jQuery('.cern-lazy-in .panel-group p, .cern-lazy-in .panel-group h1, .cern-lazy-in .panel-group h2, .cern-lazy-in .panel-group h3, .cern-lazy-in .panel-group h4, .cern-lazy-in .nav-tabs p, .cern-lazy-in .nav-tabs h1, .cern-lazy-in .nav-tabs h2, .cern-lazy-in .nav-tabs h3, .cern-lazy-in .nav-tabs h4, .cern-lazy-in .tab-content p, .cern-lazy-in .tab-content h1, .cern-lazy-in .tab-content h2, .cern-lazy-in .tab-content h3, .cern-lazy-in .tab-content h4, .cern-lazy-in .modal p, .cern-lazy-in .modal h1, .cern-lazy-in .modal h2, .cern-lazy-in .modal h3, .cern-lazy-in .modal h4, .cern-lazy-in table p, .cern-lazy-in table h1, .cern-lazy-in table h2, .cern-lazy-in table h3, .cern-lazy-in table h4').each(function () {
  //     jQuery(this).removeClass('cern-lazy-out-content');
  //     jQuery(this).removeClass('cern-lazy-in-content');
  //   });
  // }

  // first content charge, class 
  // jQuery(window).on('scroll', function (event) {
  //   jQuery('.cern-lazy-in').removeClass('lazy-first-charge');
  // });

  // up & down scroll
  // var lastScrollTop = 0;
  // jQuery(window).on('scroll', function () {
  //   var st = jQuery(this).scrollTop();
  //   if (st < lastScrollTop) {
  //     if (jQuery('.cern-lazy-in').length > 0) {
  //       jQuery('.cern-lazy-in').addClass('cern-lazy-up');
  //       jQuery('.cern-lazy-in').removeClass('cern-lazy-down');
  //     }
  //     if (jQuery('.component-row.is-cern-lazy').length > 0) {
  //       jQuery('.component-row.is-cern-lazy').addClass('cern-lazy-up');
  //       jQuery('.component-row.is-cern-lazy').removeClass('cern-lazy-down');
  //     }
  //   } else {
  //     if (jQuery('.cern-lazy-in').length > 0) {
  //       jQuery('.cern-lazy-in').addClass('cern-lazy-down');
  //       jQuery('.cern-lazy-in').removeClass('cern-lazy-up');
  //     }
  //     if (jQuery('.component-row.is-cern-lazy').length > 0) {
  //       jQuery('.component-row.is-cern-lazy').addClass('cern-lazy-down');
  //       jQuery('.component-row.is-cern-lazy').removeClass('cern-lazy-up');
  //     }
  //   }
  //   lastScrollTop = st;
  // });

})(jQuery, Drupal);
