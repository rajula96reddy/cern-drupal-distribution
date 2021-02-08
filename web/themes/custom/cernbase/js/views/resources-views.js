(function ($, Drupal) {

    'use strict';

    jQuery(function () {
        jQuery(".cern-view-display-page.cern-view-display-resources form.views-exposed-form .form-item:first-child").each(function () {

        });

    });


    // STORY RESOURCES
    jQuery(function () {
        jQuery('.cern-view-display-block.cern-view-display-story_resources .view-content .views-row').each(function () {
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

        jQuery('.cern-view-display-block.cern-view-display-story_resources').each(function () {
            var firstItem = jQuery(this).find('.views-row').first();
            var firstBG = firstItem.find('a.cboxElement').attr('href');
            var textViewSlideshow = jQuery(this).find('.view-header p').text();
            jQuery(this).find('.view-header p').html('<a href="' + firstBG + '" class="group-story-resources cboxElement cbox-element-page"><span>U</span>' + textViewSlideshow + '</a>');
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
        jQuery('.cern-view-display-block.cern-view-display-story_resources')
            .on(event, ' .view-content .owl-item .views-row .box-pattern', function () {
                location.href = jQuery(this).find('.preview-card__title a').attr('href');
            });

    });


    // MENU FEATURED
    jQuery(function () {
        var items2Trim = jQuery('.cern-view-display-block.view-cern-news-menu-featured .view-content .views-row:nth-child(3) .component-preview-cards .component-preview-cards__box_wrapper h3.standard-title a,' +
            '.cern-view-display-block.view-cern-news-menu-featured .view-content .views-row:nth-child(4) .component-preview-cards .component-preview-cards__box_wrapper h3.standard-title a');
        items2Trim.each(function(item){
            var text = $(this).text();
            if (text.trim().length > 50) {
                $(this).text(text.trim().substring(0,50) + '...' );
            }
        });
    })

})(jQuery, Drupal);
