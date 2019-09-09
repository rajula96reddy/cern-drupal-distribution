(function (jQuery, Drupal) {

    'use strict';

    var timeOutID = null;
    /**
     * Calculate ViewPort
     */
    jQuery.fn.isTopOnTopMargin = function (offset) {
        if (jQuery('#toolbar-administration').length > 0) {
            offset = offset + 76;
        }
        var distance = jQuery(this).offset().top;
        var elementBottom = distance + jQuery(this).outerHeight();
        if (jQuery(this).next('.field--item').length > 0) {
            var finalDistance = jQuery(this).next('.field--item').offset().top;
        } else {
            var finalDistance = elementBottom;
        }
        if (distance <= jQuery(window).scrollTop() + 76 + offset && jQuery(window).scrollTop() + 76 + offset <= finalDistance) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Controling Scroll Up / Scroll Down
     */
    var lastScrollTopPageNav = 0;
    window.addEventListener("scroll", function () {
        var st = window.pageYOffset || document.documentElement.scrollTop;
        if (st > lastScrollTopPageNav) {
            var nextActive = null;
            jQuery('.section-navigation').each(function () {
                jQuery(this).removeClass('active');
                if (jQuery(this).isTopOnTopMargin(60)) {
                    nextActive = jQuery(this);
                }
            });
            if (nextActive) {
                nextActive.addClass('active');
            }
        } else {
            jQuery('.section-navigation').each(function () {
                jQuery(this).removeClass('active');
                if (jQuery(this).isTopOnTopMargin(5)) {
                    jQuery(this).addClass('active');
                }
            });
            var element = jQuery('.section-navigation.active:last');
            jQuery('.section-navigation.active').each(function () {
                jQuery(this).removeClass('active');
            });
            element.addClass('active');

            if(jQuery(window).scrollTop() === 0) {
                if (!jQuery('.section-navigation').first().hasClass('s1')) {                    
                    jQuery('.page-navigation-progress').css('width', '0px');
                }
            }
        }
        lastScrollTopPageNav = st;
    }, false);

    /**
     * Controlling Scroll
     */
    var distance = jQuery('.section-navigation').offset().top;
    jQuery(window).scroll(function () {
        jQuery('.subMenu .inner a').each(function () {
            jQuery(this).closest('.subNavBtn').removeClass('active');
            var hash = jQuery(this).attr('href').substring(1);
            if (jQuery('.section-navigation.' + hash).hasClass('active')) {
                jQuery(this).closest('.subNavBtn').addClass('active');
            }
        });
        if (jQuery('.subMenu .subNavBtn.active').length > 0) {
            if (jQuery('.subMenu .subNavBtn.active').position() != 'undefined') {
                var activePosition = jQuery('.subMenu .subNavBtn.active').position().top;
                var activeWidth = jQuery('.subMenu .subNavBtn.active').innerWidth() / 2;
                jQuery('.page-navigation-progress').css('width', activePosition + activeWidth + 'px');
            }
        }
    });
    document.body.addEventListener("mousewheel", function () {
        if (timeOutID) {clearTimeout(timeOutID);}
    });


    /**
     * Init Method
     */
    jQuery(function () {
        jQuery('#page-navigation').hide();
        setTimeout(function () {
            jQuery('.section-navigation').each(function () {
                jQuery(this).closest('.field--item').addClass('force-relative');
            });
            var sumatorio = -35;
            jQuery('.section-navigation').each(function (i, v) {
                var height = jQuery(this).closest('.field--item').height();
                jQuery(this).attr("data-goto", sumatorio + 1);
                sumatorio = sumatorio + height;
                // cover-parallax-full fixes
                if (jQuery('body').hasClass('has-header')) {
                    if (jQuery('body').hasClass('sticky-header')) {;
                    } else {
                        if (jQuery(this).closest('.field--item').hasClass('cover-parallax-full')) {
                            sumatorio = sumatorio + 40;
                        }
                    }
                } else {
                    if (jQuery('body').hasClass('sticky-header')) {;
                    } else {
                        if (jQuery(this).closest('.field--item').hasClass('cover-parallax-full')) {
                            sumatorio = sumatorio + 76;
                        }
                    }
                }
            });

            jQuery('#page-navigation').fadeIn(200);
            jQuery('#page-navigation').closest('.field--item').insertAfter('footer');
            var i = 0;
            var innerHtml = '';
            jQuery(".section-navigation").each(function () {
                if (jQuery(this).data('title') != undefined && i < 7) {
                    i++;
                    jQuery(this).addClass('s' + i);
                    jQuery(this).attr('data-navigation', 's' + i);
                    innerHtml += '<span  class="subNavBtn s' + i + '"><a href="#s' + i + '"><span>' + jQuery(this).data('title') + '</span></a></span>'
                }
            });
            jQuery("#page-navigation .inner").html(innerHtml);
            jQuery('#page-navigation').closest('div.field--item').css('z-index', 500);
            jQuery('#page-navigation').closest('div.field--item').css('position', 'absolute');
            setSizeLinks();

            var activePosition = 0;
            var activeWidth = 0;
            if (jQuery('.section-navigation').first().hasClass('s1')) {
                if (jQuery('.subMenu .inner .subNavBtn.active').length == 0) {
                    jQuery('.subMenu .inner .subNavBtn:first').addClass('active');
                }
            }
            activePosition = (jQuery('.subMenu .subNavBtn.active').length > 0) ? jQuery('.subMenu .subNavBtn.active').position().top : 0;
            activeWidth = (jQuery('.subMenu .subNavBtn.active').length > 0) ? jQuery('.subMenu .subNavBtn.active').innerWidth() / 2 : 0;
            jQuery('.page-navigation-progress').css('width', activePosition + activeWidth + 'px');

            jQuery('.section-navigation').each(function () {
                jQuery(this).closest('.field--item').removeClass('force-relative');
            });

            if (jQuery(document).scrollTop() > 0) {
                jQuery("html, body").stop().animate({
                    scrollTop: jQuery(document).scrollTop() - 1
                }, 0);
                jQuery("html, body").stop().animate({
                    scrollTop: jQuery(document).scrollTop() + 1
                }, 0);
            }
            if (jQuery('.section-navigation').first().hasClass('s1')) {
                if (jQuery('.subMenu .inner .subNavBtn.active').length == 0) {
                    jQuery('.subMenu .inner .subNavBtn:first').addClass('active');
                }
            }

        }, 3000);
    });
    jQuery(window).on('load', function (event) {
        if (jQuery('.section-navigation').first().hasClass('s1')) {
            if (jQuery('.subMenu .inner .subNavBtn.active').length == 0) {
                jQuery('.subMenu .inner .subNavBtn:first').addClass('active');
            }
        }
    });

    var ua = navigator.userAgent,
    event = (ua.match(/iPad/i)) ? "touchstart" : "click";
    jQuery('.subMenu').off( "click").on( "click", '.inner .subNavBtn a', function () {
        var $item = jQuery(this);
        jQuery('.section-navigation').each(function () {
            jQuery(this).closest('.field--item').addClass('force-relative');
        });
        calculateTops(false);
        var scrollSpeed = 600;
        var mySelector = '.section-navigation';
        var hash = $item.attr('href').substring(1);
        var goTo = {
            top: 0
        };
        goTo.top = parseInt(jQuery(mySelector + '.' + hash).data("goto")) + 5;
        if ($item.closest('.subNavBtn').index() == 0 ) {
            if (jQuery('.section-navigation').first().hasClass('s1')) {
                goTo = {
                    top: 0
                };
            }
        }
        setTimeout(function () {
            jQuery("html, body").stop().animate({
                scrollTop: goTo.top
            }, scrollSpeed, 'swing', function () {
                $item.closest('.subNavBtn').addClass('active');
                jQuery('.field--item').each(function () {
                    jQuery(this).closest('.field--item').removeClass('force-relative');
                });
            });
        }, 300);
    });

    /**
     * Settings Size of labels
     */
    function setSizeLinks() {
        var totalChars = 0;
        jQuery('.subMenu .inner .subNavBtn span').each(function () {
            totalChars += jQuery(this).text().length;
        });
        jQuery('.subMenu .inner .subNavBtn').each(function () {
            var charsLength = jQuery(this).find('span').text().length;
            var percent = (charsLength * 100) / totalChars;
            jQuery(this).css('width', percent + '%')
        });
    }

    /**
     * 
     * @param restore 
     */
    function calculateTops(restore) {
        jQuery('.section-navigation').each(function () {
            jQuery(this).closest('.field--item').addClass('force-relative');
        });
        var sumatorio = -35;
        jQuery('.section-navigation').each(function (i, v) {
            var height = jQuery(this).closest('.field--item').height();
            jQuery(this).attr("data-goto", sumatorio + 1);
            sumatorio = sumatorio + height;
            // cover-parallax-full fixes
            if (jQuery('body').hasClass('has-header')) {
                if (jQuery('body').hasClass('sticky-header')) {;
                } else {
                    if (jQuery(this).closest('.field--item').hasClass('cover-parallax-full')) {
                        sumatorio = sumatorio + 40;
                    }
                }
            } else {
                if (jQuery('body').hasClass('sticky-header')) {;
                } else {
                    if (jQuery(this).closest('.field--item').hasClass('cover-parallax-full')) {
                        sumatorio = sumatorio + 76;
                    }
                }
            }
        });
        if (restore) {            
            jQuery('.section-navigation').each(function () {
                jQuery(this).closest('.field--item').removeClass('force-relative');
            });
        }
    }

})(jQuery, Drupal);
