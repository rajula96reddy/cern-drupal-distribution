/**
 * @file
 * Contains the definition of the behaviour cernDevStatus.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Attaches the CERN Dev Status behavior .
   */
  Drupal.behaviors.cernDevStatus = {
    attach: function (context, settings) {
      var contactMail = $("#cern_dev_status_contact_mail").text().replace("[at]", "@");
      $("#cern_dev_status_contact_mail").html("<a href=\"mailto:" + contactMail + "\">" + contactMail + "</a>");
    }
  };
})(jQuery, Drupal);
