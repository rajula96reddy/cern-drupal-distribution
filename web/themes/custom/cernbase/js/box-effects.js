(function ($, Drupal) {

    'use strict'; 

    function BoxEffectsClass(opt) {

        var options = {
            container:      (opt && opt.container)      ? opt.container      : '.box-effects-wrapper.enable-effects',
            item:           (opt && opt.item)           ? opt.item           : '.box-pattern',
            portraitClass:  (opt && opt.portraitClass)  ? opt.portraitClass  : 'portrait',
            singleClass:    (opt && opt.singleClass)    ? opt.singleClass    : 'simple-display',
            landscapeClass: (opt && opt.landscapeClass) ? opt.landscapeClass : 'landscape'
        }

        var lastColumn = 3;
        var columnWidth = 0;
        var thisClass = this;

        /**
         * INIT METHOD
         */
        this.init = function() {  
            var toID = null;  
            if (jQuery(window).width() >= 992) {
                this.prepareCards();
                this.prepareEffects();
            }     
            setTimeout(function() {
                thisClass.resizeAdjusts();
            }, 1000);   
            jQuery( window ).resize(function() {
                if (jQuery(window).width() >= 992) {
                    if (toID) {clearTimeout(toID);}
                    toID = setTimeout(function() {
                        thisClass.prepareCards();
                        thisClass.prepareEffects();
                    }, 1000);
                }
                thisClass.resizeAdjusts();
            });
        }
    
        /**
         * ADD CLASSES AND ATTRIBUTES
         */
        this.prepareCards = function() {
            var col = 1;
            var row = 1;
            var prevPos = { top: 0, left: 0 };
            jQuery(options.container).each(function(){
                col = 1;
                row = 1;
                prevPos = jQuery(this).find(' > ' + options.item + ':first-child').position();
                jQuery(this).find(' > ' + options.item).each(function(){               
                    thisClass.cleanAttributes(jQuery(this));
                    var currentPos = jQuery(this).position();
                    if (currentPos.top > prevPos.top) { row += 1; }
                    if (currentPos.left > prevPos.left) { col += 1; }
                    if (currentPos.left < prevPos.left) { col -= 1; }
                    if (currentPos.left == 0) {  col = 1; }    
                    jQuery(this).data('col', col);
                    jQuery(this).data('row', row);
                    jQuery(this).addClass('box-col-' + col);
                    jQuery(this).addClass('box-row-' + row);
                    prevPos = jQuery(this).position();
                    if (columnWidth == 0) {
                        columnWidth = jQuery(this).width();
                    }
                });
            });
        }

        /**
         * CLEAR CLASSES AND ATTRIBUTES
         * @param {*} item 
         */
        this.cleanAttributes = function(item) {
            item.removeAttr('data-col');
            item.removeAttr('data-row');
            item.removeClass('box-force-full-width');
            item.removeClass (function (index, className) {
                return (className.match (/(^|\s)box-col-\S+/g) || []).join(' ');
            });
            item.removeClass (function (index, className) {
                return (className.match (/(^|\s)box-row-\S+/g) || []).join(' ');
            });
        }

        /**
         * FILL GAPS IN MOBILE DEVICES
         */
        this.resizeAdjusts = function() {
            jQuery('.box-effects-wrapper').each(function(){
                var firstPos = jQuery(this).find(' > ' + options.item + ':first-child').position();
                var prevBox = null;
                jQuery(this).find(' > ' + options.item).each(function(){
                    jQuery(this).removeClass('box-force-full-width');
                });
                jQuery(this).find(' > ' + options.item).each(function(){
                    var containerWidth = jQuery(this).closest('.box-effects-wrapper').width();
                    if (jQuery(this).position().left == 0) {
                        if (prevBox != null && prevBox.position().left == 0) {
                            if (prevBox.width() < (containerWidth * 0.60)) {
                                prevBox.addClass('box-force-full-width');
                            }
                        }
                    }
                    if ( jQuery(this).is( ":last-child" ) ) {
                        if (jQuery(this).position().left == firstPos.left) {
                            if (jQuery(this).width() < (containerWidth * 0.60)) {
                                jQuery(this).addClass('box-force-full-width');
                            }
                        }
                    }
                    prevBox = jQuery(this);
                });
            });
        }
    
        /**
         * MOUSE EVENTS CONTROL
         */
        this.prepareEffects = function() {
            jQuery( options.container + ' > ' + options.item )
                .mouseover(function(e) {
                    var item = jQuery(this);
                    thisClass.onHover(item);
                    e.preventDefault();
                })
                .mouseout(function(e) {
                    var item = jQuery(this);
                    thisClass.onBlur(item);
                    e.preventDefault();
                });
        }
    
        /**
         * ON HOVER EVENT
         */
        this.onHover = function(item) {  
            var type = thisClass.getType(item);
            var container = item.closest(options.container);
            var affected = thisClass.affectedCards(item.data('col'),item.data('row'),type,container); 
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
         * ON BLUR EVENT
         */
        this.onBlur = function(item) {  
            var type = thisClass.getType(item);
            var container = item.closest(options.container);
            var affected = thisClass.affectedCards(item.data('col'),item.data('row'),type,container); 
            jQuery.each(affected.bigger, function(k,v){
                container.find(v).removeClass('make-bigger');
                if (container.find(v) > columnWidth) {
                    container.find(v).addClass('from-bigger');
                } else if (container.find(v) < columnWidth) {
                    container.find(v).addClass('from-smaller');
                }
            }); 
            item.removeClass('make-bigger');
            item.addClass('from-bigger');
            jQuery.each(affected.smaller, function(k,v){
                container.find(v).removeClass('make-smaller');
                if (container.find(v) > columnWidth) {
                    container.find(v).addClass('from-bigger');
                } else if (container.find(v) < columnWidth) {
                    container.find(v).addClass('from-smaller');
                }
            }); 
        }
    
        /**
         * FIND CARDS AFFECTED BY THE HOVERED OR BLURED ONE
         */
        this.affectedCards = function(col, row, type, container) {
            var returnArray = { bigger: [], smaller: [] };
            if (type == options.singleClass) {
                if (col == lastColumn) {
                    returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + row);
                    if (container.find('.box-col-' + (col-1) + '.box-row-' + (row-1)).hasClass(options.portraitClass)) {
                        returnArray.smaller.push('.box-col-' + (col-1) + '.box-row-' + (row-1));
                    }   
                    if (container.find('.box-col-' + (col-1) + '.box-row-' + row).hasClass(options.portraitClass)) {
                        returnArray.bigger.push('.box-col-' + (col) + '.box-row-' + (row+1));
                    }                    
                    if (container.find('.box-col-' + (col-1) + '.box-row-' + (row-1)).hasClass(options.portraitClass)) {
                        returnArray.bigger.push('.box-col-' + (col) + '.box-row-' + (row-1));
                    }
                } else {
                    returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + row);
                    if (container.find('.box-col-' + (col+1) + '.box-row-' + (row-1)).hasClass(options.portraitClass)) {
                        returnArray.smaller.push('.box-col-' + (col+1) + '.box-row-' + (row-1));
                    }
                }
            }
            if (type == options.portraitClass) {
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

        /**
         * GET TYPE OF CARD
         * @param {*} item 
         */
        this.getType = function(item) {            
            var type = 
                (item.hasClass(options.portraitClass)) ? options.portraitClass
                : (item.hasClass(options.singleClass)) ? options.singleClass 
                : options.landscapeClass ;

            return type
        }
        
    }
    
    var CPreviewCardsClass = new BoxEffectsClass();
    jQuery(function() {
        CPreviewCardsClass.init();
    });

})(jQuery, Drupal);