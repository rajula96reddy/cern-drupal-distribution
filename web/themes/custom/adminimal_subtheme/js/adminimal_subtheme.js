(function ($, Drupal) {
    Drupal.behaviors.customCKEditorConfig = {
        attach: function (context, settings) {
            if (typeof CKEDITOR !== "undefined") {
                CKEDITOR.dtd.$removeEmpty['i'] = false;
                CKEDITOR.dtd.$removeEmpty['span'] = false;
            }
            // check if CERN tollbar is ON
            if (jQuery('#cern-toolbar').length > 0) {
                jQuery('body').addClass('cern-toolbar');
            }
            // check if ADMIN tollbar is ON
            if (jQuery('#toolbar-bar').length > 0) {
                jQuery('.toolbar-item').each(function () {
                    var ToolbarItem = $(this).text().toLowerCase();
                    $(this).addClass(ToolbarItem);
                })
            }

        }
    }
})
(jQuery, Drupal);
