(function ($, Drupal) {

  'use strict';

    /**
     * Initializes the open on hover functionality of the menu
     *
     * @param dropdownElements
     */
    function dropdownElementsOpenOnHoverInitialization(dropdownElements){
        dropdownElements.each(function () {

            $(this).hover(function () {
                $(this).addClass('open hovered');
            }, function() {
                let menu = $(this);
                $(menu).removeClass('hovered');
                setTimeout(function(){
                    // if the menu was not hovered again
                    if($(menu).hasClass('hovered') === false){
                        $(menu).removeClass('open');
                    }

                }, 150);

            });
        });
    }

    /**
     * Removes the open class from dropdowns if exists
     *
     * @param dropdownElements the array of dropdown elements
     */
    function removeOpenDropdowns(dropdownElements){
        dropdownElements.each(function () {
            if ($(this).hasClass("open")){
                $(this).removeClass("open");
            }

        });
    }

    /**
     * Adds the open class from dropdowns if does not exist
     *
     * @param dropdownElements the array of dropdown elements
     */
    function addOpenDropdowns(dropdownElements){
        dropdownElements.each(function () {
            if (!$(this).hasClass("open")){
                $(this).addClass("open");
            }

        });
    }

    /**
     * Handles burger button on load
     */
    function initializeburgerButtonFunctionalities(){
        $('button.navbar-toggle').click(function(){
            let header = $('header');
            if ($(header).hasClass('menu-expanded')){
                header.removeClass("menu-expanded");
            }
            else{
                header.addClass("menu-expanded");
            }
        });
    }

    /**
     * Hides the burger menu and its functionalitites
     *
     */
    function hideBurgerMenu(){
        let collapseNavbar = $('.navbar-collapse.collapse');
        let header = $('header');

        if (collapseNavbar.hasClass("in") ){
            collapseNavbar.removeClass("in");
        }
        if (header.hasClass("menu-expanded")){
            header.removeClass("menu-expanded");
        }
    }

    /**
     * Initializes important functionalities of the language switcher
     *
     */
    function initializelanguageSwitcherFunctionalities(){
        if ($('header .block-language').length > 0) {
            $('header .block-language ul.links li').each(function () {
                if ($(this).hasClass('is-active')) {
                    $(this).css('display', 'none');
                    var activeLanguage = $(this).find('a').text();
                    $('header .block-language').append('<div class="active-language"><a href="javascript:;"><span class="separator">|</span>' + activeLanguage + '<span class="caret"></span></a></div>');
                }
            });
        }

        if ($('header .active-language').length > 0) {
            $('header .active-language a').click(function () {
                $('header .block-language > ul.links').toggleClass('language-expanded');
            })
        }

        $(window).click(function () {
            $('header .block-language > ul.links').removeClass('language-expanded');
        });

        $('header .active-language a').click(function (event) {
            event.stopPropagation();
        });
    }

    //page loading
    $(document).ready(function () {

        initializeburgerButtonFunctionalities();
        initializelanguageSwitcherFunctionalities();

        //menu hover feature
        let dropdownElements = $('.region-header .main-menu .dropdown');
        if (dropdownElements.length > 0) {
          if (($(window).width() >= 1080)) { //check for mobile mode
            dropdownElementsOpenOnHoverInitialization(dropdownElements);
          }
          else { //if mobile mode, don't enable the hover functionality and instead open the complete menu
            dropdownElements.addClass('open');
          }
        }

        $(window).on('resize', function () {
            let win = $(this);

            initializeburgerButtonFunctionalities();
            // if outside burger menu limit
            if (win.width() >= 1080){
                hideBurgerMenu();   //hides the burger menu
                dropdownElementsOpenOnHoverInitialization(dropdownElements);    //initializes hover func of dropdown
                removeOpenDropdowns(dropdownElements);  //closes expanded dropdown elements
            }
            else{
                addOpenDropdowns(dropdownElements);
                dropdownElements.each(function () {
                    $(this).unbind('mouseenter mouseleave');
                });
            }
        });

    });
  
})(jQuery, Drupal);
