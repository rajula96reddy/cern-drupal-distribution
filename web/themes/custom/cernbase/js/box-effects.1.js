(function ($, Drupal) {

    'use strict'; 

    function PreviewCardsClass() {

        var lastColumn = 3;
        var col = 1;
        var row = 1;
        var prevPos = { top: 0, left: 0 };
        var columnWidth = 0;

        this.init = function() {
            this.prepareCards();
            this.prepareEffects();
        }
    
        /**
         * 
         */
        this.prepareCards = function() {
            jQuery('.component-row__column > .component-preview-cards').each(function(){
                var currentPos = jQuery(this).position();
                if (currentPos.top > prevPos.top) { row += 1; }
                if (currentPos.left > prevPos.left) { col += 1; }
                if (currentPos.left < prevPos.left) { col -= 1; }
                if (currentPos.left == 0) {  col = 1; }
                jQuery(this).attr('data-col', col);
                jQuery(this).attr('data-row', row);
                jQuery(this).addClass('box-col-' + col);
                jQuery(this).addClass('box-row-' + row);
                prevPos = jQuery(this).position();
                if (columnWidth == 0) {
                    columnWidth = jQuery(this).width();
                }
            });
        }
    
        /**
         * 
         */
        this.prepareEffects = function() {
            jQuery( '.component-row__column > .component-preview-cards' )
                .mouseover(function() {
                    var item = jQuery(this);
                    CPreviewCardsClass.onHover(item);
                })
                .mouseout(function() {
                    var item = jQuery(this);
                    CPreviewCardsClass.onBlur(item);
                });
        }
    
        /**
         * 
         */
        this.onHover = function(item) {  
            var type = 
                (item.hasClass('portrait')) ? 'portrait' 
                : (item.hasClass('simple-display')) ? 'simple-display'
                : 'landscape' ;
            var container = item.closest('.component-row__column');
            var affected = CPreviewCardsClass.affectedCards(item.data('col'),item.data('row'),type,container); 
            jQuery.each(affected.smaller, function(k,v){
                container.find(v).removeClass('from-bigger');
                container.find(v).removeClass('from-smaller');
                container.find(v).addClass('make-smaller');
            }); 
            item.removeClass('from-bigger');
            item.removeClass('from-smaller');
            item.addClass('make-bigger');
            jQuery.each(affected.bigger, function(k,v){
                container.find(v).removeClass('from-bigger');
                container.find(v).removeClass('from-smaller');
                container.find(v).addClass('make-bigger');
            }); 
        }
    
        /**
         * 
         */
        this.onBlur = function(item) {   
            var type = 
                (item.hasClass('portrait')) ? 'portrait' 
                : (item.hasClass('simple-display')) ? 'simple-display'
                : 'landscape' ;
            var container = item.closest('.component-row__column');
            var affected = CPreviewCardsClass.affectedCards(item.data('col'),item.data('row'),type,container); 
            jQuery.each(affected.bigger, function(k,v){
                container.find(v).removeClass('make-bigger');
                //container.find(v).removeClass('from-smaller');
                if (container.find(v) > columnWidth) {
                    container.find(v).addClass('from-bigger');
                } else if (container.find(v) < columnWidth) {
                    container.find(v).addClass('from-smaller');
                }
            }); 
            item.removeClass('make-bigger');
            //item.removeClass('from-smaller');
            item.addClass('from-bigger');
            jQuery.each(affected.smaller, function(k,v){
                container.find(v).removeClass('make-smaller');
                //container.find(v).removeClass('from-bigger');
                if (container.find(v) > columnWidth) {
                    container.find(v).addClass('from-bigger');
                } else if (container.find(v) < columnWidth) {
                    container.find(v).addClass('from-smaller');
                }
                //container.find(v).addClass('from-smaller');
            }); 
        }
    
        /**
         * 
         */
        this.affectedCards = function(col, row, type, container) {
            var returnArray = { bigger: [], smaller: [] };
            if (type == 'simple-display') {
                if (col == lastColumn) {
                    //if (container.find('.box-col-' + (col-1) + '.box-row-' + row).length > 0) {
                        returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + row);
                    //} 
                    //else
                    if (container.find('.box-col-' + (col-1) + '.box-row-' + (row-1)).hasClass('portrait')) {
                        returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + (row-1));
                    }
                    

                    if (container.find('.box-col-' + (col-1) + '.box-row-' + row).hasClass('portrait')) {
                        returnArray.bigger.push('.box-col-' + (col) + '.box-row-' + (row+1));
                    }
                    
                    if (container.find('.box-col-' + (col-1) + '.box-row-' + (row-1)).hasClass('portrait')) {
                        returnArray.bigger.push('.box-col-' + (col) + '.box-row-' + (row-1));
                    }


                } else {
                    //if (container.find('.box-col-' + (col+1) + '.box-row-' + row).length > 0) {
                        returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + row);
                    //} 
                    //else
                    if (container.find('.box-col-' + (col+1) + '.box-row-' + (row-1)).hasClass('portrait')) {
                        returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + (row-1));
                    }
                }
            }
            if (type == 'portrait') {
                if (col == lastColumn) {
                    returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + row);
                    returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + (row+1));
                } else {
                    returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + row);
                    returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + (row+1));
                }
            }
            return returnArray;
        }
        
    }
    
    var CPreviewCardsClass = new PreviewCardsClass();
    jQuery(function() {
        CPreviewCardsClass.init();
    });

})(jQuery, Drupal);