(function ($, Drupal) {

    'use strict';

    var ua = navigator.userAgent,
    event = (ua.match(/iPad/i) || ua.match(/iPhone/i)) ? "touchstart" : "click";
    $("#block-mainnavigation").off("touchstart click").on("touchstart click", "li.cern-dropdown > a", function(event){
        event.preventDefault();
        event.stopPropagation();
        //alert("test");

        if ($("body").hasClass("open-cern-menu")) {
            if ($(this).closest("li.cern-dropdown").hasClass("open")) {
                $(this).closest("li.cern-dropdown").toggleClass("open");
                $("body").toggleClass("open-cern-menu");
                $("html").toggleClass("open-cern-menu");
            } else {
                $("#block-mainnavigation li.cern-dropdown").each(function(){
                    $(this).removeClass("open");
                });
                $(this).closest("li.cern-dropdown").toggleClass("open");
            }
        } else {
            $(this).closest("li.cern-dropdown").toggleClass("open");
            $("body").toggleClass("open-cern-menu");
            $("html").toggleClass("open-cern-menu");
        }

        // calculate vertical separator
        /* if (jQuery(window).width() > 1200) {
            jQuery('.block-mainnavigation-cern-megamenu nav.navbar li.cern-dropdown').each(function(){
                jQuery(this).find('div.cern-dropdown-menu.row > div.col-item-megamenu').each(function(){
                    jQuery(this).css('height', '');
                });
                var height = 0;
                jQuery(this).find('div.cern-dropdown-menu.row > div.col-item-megamenu').each(function(){
                    var tmp = jQuery(this).height();
                    height = (tmp > height) ? tmp : height;
                });
                jQuery(this).find('div.cern-dropdown-menu.row > div.col-item-megamenu').each(function(){
                    jQuery(this).css('height', height+'px');
                });
            });
        } else {
            jQuery('.block-mainnavigation-cern-megamenu nav.navbar li.cern-dropdown').each(function(){
                jQuery(this).find('div.cern-dropdown-menu.row > div.col-item-megamenu').each(function(){
                    jQuery(this).css('height', '');
                });
            });
        } */
    });

    $("#block-mainnavigation").on("click", "button.navbar-toggle", function() {
        $(this).closest("#block-mainnavigation").find('.cern-dropdown.open').removeClass('open');
        $("body").removeClass("open-cern-menu");
        $("html").removeClass("open-cern-menu");
    });

    $("#block-mainnavigation").on("click", "div.close-cern-dropdown", function(event){
        event.preventDefault();
        $(this).closest("li.cern-dropdown").toggleClass("open");
        $("body").toggleClass("open-cern-menu");
        $("html").toggleClass("open-cern-menu");
    });


    $(function(){
        setSearchPlaceholder();
    });

    $( window ).resize(function() {
        setSearchPlaceholder();
    });

    function setSearchPlaceholder() {
        if (jQuery(window).width() >= 767) {
            $(".cern-menu-search").each(function(){
              let placeholderSearch = $(this).find(".placeholder-of-search").text();
              $(this).find("input.form-search").attr("placeholder", placeholderSearch);
              $(this).find("input.form-search").removeAttr("data-original-title");
            });
        } else {
            $(".cern-menu-search").each(function(){
              let placeholderSearch = $(this).find(".placeholder-of-search-mobile").text();
              $(this).find("input.form-search").attr("placeholder", placeholderSearch);
              $(this).find("input.form-search").removeAttr("data-original-title");
            });
        }
    }

    /** ACTIVE TRAIL */
    var current_pathname = window.location.pathname;
    if(current_pathname != '/') {
        var current_search = window.location.search;
        var current_path = current_pathname.split('/');
        var current_path_length = current_path.length;
        var matched_links = null;
        var previous_path = '';
        var i = 0;
        for (i; i < (current_path_length - 1); i++) {
          previous_path = current_path.join('/');
          matched_links = $('li.cern-dropdown').find('a[href^="'+ previous_path +'"]');
          if ((matched_links.length > 1 && current_search != '') || matched_links.length == 1) {
            break;
          }
          current_path.pop();
        }
     
        if (matched_links.length > 1  && current_search != '') {
          var matched_links = $('li.cern-dropdown').find('a[href^="'+ previous_path + current_search +'"]');
        }
    
        if (matched_links.length == 1) {
          // make a row children link active
          matched_links.eq(0).addClass('active-trail');
        } 
        
        if(matched_links.length >= 1) {
          // make a parent as active
          matched_links.eq(0).closest('.cern-dropdown').find('a.dropdown-toggle').eq(0).addClass('active-trail');
        }
    }
     
    /** END ACTIVE TRAIL */
    
})(jQuery, Drupal);    
