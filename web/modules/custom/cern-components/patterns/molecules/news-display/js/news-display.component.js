(function (jQuery, Drupal) {

    'use strict';

    jQuery(function() {
        jQuery(".news-node-full-content-created-date-author p.news-node-full-content-author a:not(:last-child):not(:nth-last-child(2))").after("<span>, </span>");
        jQuery(".news-node-full-content-created-date-author p.news-node-full-content-author a:nth-last-child(2)").after("<span> & </span>");
        jQuery(".news-node-full-content-file table").addClass("table");
        jQuery(".news-node-full-content-file table").addClass("table-striped");
    });

    
})(jQuery, Drupal);
