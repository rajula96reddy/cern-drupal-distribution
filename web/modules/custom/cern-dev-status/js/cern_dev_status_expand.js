var triggeredCernDevStatusCookie = false;
/**
 * @file
 * Contains the definition of the behaviour cernDevStatusExpand.
 */

(function ($, Drupal) {
    'use strict';
    /**
     * Attaches the CERN Dev Status behavior .
     */
    Drupal.behaviors.cernDevStatusExpand = {
        attach: function (context, settings) {
            $('body', context).addClass('dev-status-ui');
            $('#dev-status-message', context).addClass('open').removeClass('js-disabled');
            $('#dev-status-action', context).once().on('click', function () {
                $('#dev-status-message-content', context).slideToggle(50);
                $('#dev-status-message', context).toggleClass('open closed');
                if ($('#dev-status-message', context).hasClass('closed')) {
                    $.cookie('cern-dev-status-closed', 'true', { path: '/' });
                } else {
                    $.cookie('cern-dev-status-closed', 'false', { path: '/' });
                }
            });

            if (!triggeredCernDevStatusCookie){
                triggeredCernDevStatusCookie = true;
                if($.cookie('cern-dev-status-closed') == 'true') {
                    $('#dev-status-action').trigger('click');
                }
            }
        }
    };
})(jQuery, Drupal);
