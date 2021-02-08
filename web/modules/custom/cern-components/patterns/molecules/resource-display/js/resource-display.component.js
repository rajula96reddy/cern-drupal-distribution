(function ($, Drupal) {

    'use strict';

    $(function(){
        $('.resource-node-center-content').on('click', '.owl-thumb-item', function(){
            var clickedSRC = $(this).css('background-image');
            clickedSRC = clickedSRC.replace('url(','').replace(')','').replace(/\"/gi, "");
            var thumbItem = $(this);
            $('.resource-node-center-content p.cds-media-info').each(
                function(){
                    $(this).hide();
                }
            );
            $('.resource-node-center-content p.cds-media-info').each(
                function(){
                    if (clickedSRC.indexOf($(this).data('cdsid')) >= 0) {
                        console.log("entro 1");
                        $(this).addClass('cern-lazy-in-content');
                        $(this).removeClass('cern-lazy-out-content');
                        $(this).show();
                    } else if (clickedSRC.indexOf('data:image') >= 0) {
                        console.log("entro 2");
                        var activeSlide = thumbItem.closest('.gallery-carousel').find('.owl-item.active').find('figure').attr('id');
                        if (activeSlide.indexOf($(this).data('cdsid')) >= 0) {
                            $(this).addClass('cern-lazy-in-content');
                            $(this).removeClass('cern-lazy-out-content');
                            $(this).show();
                        }
                    } else {
                        $(this).hide();
                    }
                }
            );
        });

        $('.resources-node-full-content-file > table').addClass('table');
        $('.resources-node-full-content-file > table').addClass('table-hover');
        $('.resources-node-full-content-file > table').addClass('table-striped');
    });

    $(function(){
        var height = 0;
        var cintval = setInterval(function(){
            height = jQuery("#resource-iframe").contents().find("body").height();
            if (height > 0) {
                jQuery("#resource-iframe").css('height', height + 'px' );
                clearInterval(cintval);
            }
        }, 500);  
    });

})(jQuery, Drupal);
