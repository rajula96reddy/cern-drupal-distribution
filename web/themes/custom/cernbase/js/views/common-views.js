(function ($, Drupal) {

    'use strict';

    jQuery(function () {
        var excludeForms = ':not(.view-general-search):not(.cern-view-display-resources):not(.cern-view-display-feature_events):not(.cern-view-display-past_events):not(.cern-view-display-all_news):not(.cern-view-display-faq_page):not(.cern-view-display-page_taxonomies):not(.resources-mosaic)';
        exposedForms(excludeForms);
    });

    jQuery(window).resize(function () {
        var excludeForms = ':not(.view-general-search):not(.cern-view-display-resources):not(.cern-view-display-feature_events):not(.cern-view-display-past_events):not(.cern-view-display-all_news):not(.cern-view-display-faq_page):not(.cern-view-display-page_taxonomies):not(.resources-mosaic)';
        exposedForms(excludeForms);
    });

    function exposedForms(excludeForms) {
        var totalWidthForm = 0;
    }

    jQuery(window).resize(function () {
        calculateWidthOfFormItems();
    });

    jQuery(function () {
        calculateWidthOfFormItems();
    });

    /**
     * Calculates the width of form items based on screen resolution
     */
    function calculateWidthOfFormItems() {
        if($(window).width() > 1024) {
            setWidthOfFormItems(1);
        }
        else if ($(window).width() > 768) {
            setWidthOfFormItems(2)
        }
        else{
            removeWidthOfFormItems();
        }
    }

    /**
     * Removes the width of form items. Used in small resolutions in order to inherit the default values.
     */
    function removeWidthOfFormItems(){
        let form_elements = jQuery('.cern-view-display-page .views-exposed-form .form-inline .form-item');
        form_elements.each(function () {
           jQuery(this).css("width", "");
        });
    }

    /**
     * Sets the width of form items based on how many rows the forms should expand.
     *
     * @param numberOfRows
     */
    function setWidthOfFormItems(numberOfRows){
        let form_elements = jQuery('.cern-view-display-page .views-exposed-form .form-inline .form-item');
        let first_child = form_elements.first(); // the first child of the form elements
        // if first exposed for is of type textfield
        if (first_child.hasClass("form-type-textfield")) {
            let rest_of_the_items = jQuery('.cern-view-display-page .views-exposed-form .form-inline .form-item:not(:first)');
            rest_of_the_items.each(function () {
                let equal_width = 100 / rest_of_the_items.length * numberOfRows;
                jQuery(this).css("width", equal_width + "%").css("width", "-=23px");
            });
        } else {
            form_elements.each(function () {
                let equal_width = 100 / form_elements.length * numberOfRows;

                jQuery(this).css("width", equal_width + "%").css("width", "-=23px");
            });
        }

    }

    jQuery(function () {
        carruselFillGaps();
    });

    jQuery(window).resize(function () {
        clearFillGaps();
        carruselFillGaps();
    });

    function clearFillGaps() {
        jQuery(':not(.ux-library) .cern-view-display-block').each(function () {
            jQuery(this).find('.owl-carousel .owl-item').each(function () {
                var itemWrapper = jQuery(this).find('.row');
                if (itemWrapper.find('.views-row').length < 3) {
                    itemWrapper.find('.views-row').each(function () {
                        jQuery(this).css('width', '');
                    });
                }
            });
        });
    }

    function carruselFillGaps() {
        if (jQuery(window).width() >= 992) {
            var idCarrusel = 0;
            jQuery(':not(.ux-library) .cern-view-display-block:not(.cern-view-display-story_resources):not(.resources-mosaic):not(.cern-view-display-upcoming_events):not(.events-collision)').each(function () {
                idCarrusel++;
                jQuery(this).find('.owl-carousel .owl-item').each(function () {
                    var itemWrapper = jQuery(this).find('.row');

                    if (itemWrapper.find('.views-row').length == 2) {
                        itemWrapper.find('.views-row').each(function () {
                            jQuery(this).css('width', 'calc(50% - 16px)');
                        });
                    }

                    if (itemWrapper.find('.views-row').length == 1) {
                        itemWrapper.find('.views-row').each(function () {
                            jQuery(this).css('width', 'calc(100% - 16px)');
                        });
                    }

                });
            });
        }
    }

    //custom views : events collision
    Drupal.behaviors.EventsCollision = {
        attach: function (context, settings) {
            /* Upcoming events carousel */

            jQuery(function () {
                jQuery('.events-collision .view-content.owl-carousel')
                    .prepend('<button class="bubbly-button"></button>');
            });

            var bubblyTimeoutID = null;
            var animateButton2 = function (item) {
                //reset animation
                if (bubblyTimeoutID) {
                    clearTimeout(bubblyTimeoutID);
                }
                item.removeClass('animate');

                setTimeout(function () {
                    item.addClass('animate');
                    bubblyTimeoutID = setTimeout(function () {
                        item.removeClass('animate');
                    }, 2000);
                }, 100);
            };

            $('.events-collision .view-content').each(function () {
                if ($(this).find('.carousel-cern-item').length > 0) {
                    var owlCollision = $(this);
                    owlCollision.owlCarousel({
                        loop: false,
                        nav: true,
                        navText: ['', ''],
                        dots: false,
                        merge: true,
                        mouseDrag: false,
                        items: 1,
                        itemElement: 'carousel-cern-item',
                        autoHeight: false
                    });
                    // Apply the theme
                    owlCollision.addClass('owl-carousel').addClass('owl-theme');
                    owlCollision.on('translate.owl.carousel', function (event) {
                        // var position = $('.owl-dot.active').position().left;
                        owlCollision.filter('.owl-dots .owl-dot:first-child span').animate({
                            // left: position - 5,
                        }, 200, "linear");
                    });

                    // hidden the inactive slides on moves ending
                    owlCollision.on('translated.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item:not(.active)').each(function () {
                            jQuery(this).css('opacity', '0');
                        });
                    });
                    owlCollision.on('translate.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item').each(function () {
                            jQuery(this).css('opacity', '1');
                        });
                        animateButton2(jQuery(this).closest('.owl-carousel').find('.bubbly-button'));
                    });
                }
            });
        }
    };

    // //custom views : resources mosaic
    // Drupal.behaviors.ResourcesMosaic = {
    //     attach: function (context, settings) {
    //         jQuery(function () {
    //             jQuery(".resources-mosaic form.views-exposed-form .form-item:first-child").each(function () {
    //                 var item = jQuery(this);
    //                 var placeholder = item.find('label').text();
    //                 item.find('label').hide();
    //                 item.find('input').attr('placeholder', placeholder.toUpperCase());
    //             });
    //         });
    //     }
    // };

    //custom views : horizontal boxes
    Drupal.behaviors.HorizontalBoxes = {
        attach: function (context, settings) {
            $('.horizontal-boxes .view-content').each(function () {
                if ($(this).find('.carousel-cern-item').length > 0) {
                    var owlHorizontal = $(this);
                    owlHorizontal.owlCarousel({
                        loop: false,
                        nav: false,
                        navText: ['', ''],
                        dots: true,
                        merge: true,
                        mouseDrag: false,
                        items: 1,
                        itemElement: 'carousel-cern-item',
                        autoHeight: false
                    });
                    // extend elements on the last slide
                    owlHorizontal.on('initialized.owl.carousel', function (e) {});
                    // Apply the theme
                    owlHorizontal.addClass('owl-carousel').addClass('owl-theme');
                    owlHorizontal.on('translate.owl.carousel', function (event) {
                        // var position = $('.owl-dot.active').position().left;
                        owlHorizontal.filter('.owl-dots .owl-dot:first-child span').animate({
                            // left: position - 5,
                        }, 200, "linear");
                        if ($(window).width() >= 992) {
                            var idCarrusel = 0;
                            $('.horizontal-boxes .view-content').each(function () {
                                idCarrusel++;
                                $(this).find('.owl-item').each(function () {
                                    var itemWrapper = $(this).find('.row');

                                    if (itemWrapper.find('.views-row').length == 2) {
                                        itemWrapper.find('.views-row').each(function () {
                                            $(this).css('width', 'calc(50% - 16px)');
                                        });
                                    }

                                    if (itemWrapper.find('.views-row').length == 1) {
                                        itemWrapper.find('.views-row').each(function () {
                                            $(this).css('width', 'calc(100% - 16px)');
                                        });
                                    }

                                });
                            });
                        }
                        if ($(window).width() < 992) {
                            var idCarrusel = 0;
                            $('.horizontal-boxes .view-content').each(function () {
                                idCarrusel++;
                                $(this).find('.owl-item').each(function () {
                                    var itemWrapper = $(this).find('.row');

                                    if (itemWrapper.find('.views-row').length == 2) {
                                        itemWrapper.addClass('last-2');
                                        itemWrapper.find('.views-row').each(function () {
                                            $(this).css('height', '50vh');
                                        });
                                    }

                                    if (itemWrapper.find('.views-row').length == 1) {
                                        itemWrapper.addClass('last-1');
                                        itemWrapper.find('.views-row').each(function () {
                                            $(this).css('height', '100vh');
                                        });
                                    }

                                });
                            });
                        }
                    });

                    // hidden the inactive slides on moves ending
                    owlHorizontal.on('translated.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item:not(.active)').each(function () {
                            jQuery(this).css('opacity', '0');
                        });
                    });
                    owlHorizontal.on('translate.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item').each(function () {
                            jQuery(this).css('opacity', '1');
                        });
                    });
                }
            });
        }
    };

    //custom views : teaser list
    Drupal.behaviors.TeaserList = {
        attach: function (context, settings) {
            jQuery(function () {
                var items = 0;
                var cintval = setInterval(function () {
                    items = jQuery(".teaser-list .views-exposed-form .form-item:first-child");
                    if (items.length > 0) {
                        jQuery(".teaser-list .form-item:first-child").each(function () {
                            var placeholder = jQuery(this).find('label').text();
                            // jQuery(this).find('label').hide();
                            // jQuery(this).find('input').attr('placeholder', placeholder.toUpperCase());
                        });
                        clearInterval(cintval);
                    }
                }, 500);

            });
        }
    };

    //custom views : resources mosaic
    Drupal.behaviors.ResourcesMosaic = {
        attach: function (context, settings) {
            $('.resources-mosaic .view-content').each(function () {
                if ($(this).find('.carousel-cern-item').length > 0) {
                    var owlViews = $(this);
                    owlViews.owlCarousel({
                        loop: false,
                        nav: false,
                        merge: true,
                        mouseDrag: false,
                        items: 1,
                        itemElement: 'carousel-cern-item',
                        autoHeight: true
                    });
                    // Apply the theme
                    owlViews.addClass('owl-carousel').addClass('owl-theme');
                    owlViews.on('translate.owl.carousel', function (event) {
                        var position = $('.owl-dot.active').position().left;
                        owlViews.filter('.owl-dots .owl-dot:first-child span').animate({
                            left: position - 5,
                        }, 200, "linear");
                    });
                    // hidden the inactive slides on moves ending
                    owlViews.on('translated.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item:not(.active)').each(function () {
                            jQuery(this).css('opacity', '0');
                        });
                    });
                    owlViews.on('translate.owl.carousel', function (event) {
                        jQuery(this).find('.owl-item').each(function () {
                            jQuery(this).css('opacity', '1');
                        });
                    });

                    var maxHeight = 0;
                    owlViews.find('.views-row').each(function () {
                        maxHeight = ($(this).height() > maxHeight) ? $(this).height() : maxHeight;
                    });
                    owlViews.find('.views-row').find("> div").css('height', maxHeight + "px");
                }
            });

            $(function () {
                setTimeout(function () {
                    jQuery('.view.cern-view-display-block .view-content').each(function () {
                        if (jQuery(this).find('.carousel-cern-item').length > 0) {
                            var owlViews = jQuery(this);
                            owlViews.trigger('refresh.owl.carousel')
                        }
                    });
                }, 2500);
            });
            jQuery(function () {
                jQuery('.resources-mosaic .view-content .views-row').each(function () {
                    var element = jQuery(this);
                    var item = jQuery(this).find('.box-pattern').css('background-image');
                    if (item.indexOf("cds.cern.ch") >= 0) {
                        item = jQuery(this).find('.box-pattern').data('background');
                        element.find('.box-pattern').append('<a href="' + item + '" class="group-story-resources cboxElement cbox-element"></a>');
                    } else {
                        item = item.replace('url(', '').replace(')', '').replace(/\"/gi, "");
                        element.find('.box-pattern').append('<a href="' + item + '" class="group-story-resources cboxElement cbox-element"></a>');
                    }
                });

                jQuery('.resources-mosaic').each(function () {
                    var firstItem = jQuery(this).find('.views-row').first();
                    var firstBG = firstItem.find('a.cboxElement').attr('href');
                    var textViewSlideshow = jQuery(this).find('.view-header p').text();
                    jQuery(this).find('.view-header p').html('<a href="' + firstBG + '" class="group-story-resources cboxElement cbox-element-page">' + textViewSlideshow + '</a>');
                    firstItem.find('a.cboxElement').removeClass('group-story-resources');
                });


                $('a.group-story-resources').colorbox({
                    rel: "group-story-resources",
                    transition: "none",
                    width: "70%",
                    height: "70%",
                    scrolling: false,
                    opacity: "0.9",
                    photo: true,
                    onComplete: function () {},
                    onCleanup: function () {}
                });

                var ua = navigator.userAgent,
                    event = (ua.match(/iPad/i)) ? "touchstart" : "click";
                jQuery('.resources-mosaic')
                    .on(event, ' .view-content .owl-item .views-row .box-pattern', function () {
                        location.href = jQuery(this).find('.preview-card__title a').attr('href');
                    });

            });
        }
    };




})(jQuery, Drupal);
