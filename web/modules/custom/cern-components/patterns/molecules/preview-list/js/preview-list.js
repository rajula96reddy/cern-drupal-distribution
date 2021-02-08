(function (jQuery, Drupal) {

    'use strict';

    Drupal.behaviors.CERNComponentsPreviewList = {
        attach: function (context) {

            jQuery(".preview-list-component .preview-list-strap a:not(:last-child):not(:nth-last-child(2))").once().after("<span>, </span>");
            jQuery(".preview-list-component .preview-list-strap a:nth-last-child(2)").once().after("<span> & </span>");
            jQuery(".preview-list-component .preview-list-strap a").each(function () {
                jQuery(this).replaceWith(jQuery('<span>' + this.innerHTML + '</span>'));
            });
        }
    };

})(jQuery, Drupal);
