(function ($, Drupal) {
  'use strict';
  Drupal.behaviors.CERNComponentsAgendaComingSoon = {
    attach: function() {
      var $countdown = $('.agenda-coming-soon-countdown');
      var start_date = $countdown.data('countdown-start-date') * 1000; //multiplying by 1000 because Javascript Dates
                                                                // require millisecond UNIX timestamps and not seconds
      $countdown.once().countdown(start_date)
      .on('update.countdown', function(event) {
        $(this).find('.days').html(event.strftime('%-D'));
        $(this).find('.days-label').html(event.strftime('day%!D'));
        $(this).find('.hours').html(event.strftime('%-H'));
        $(this).find('.hours-label').html(event.strftime('hour%!H'));
        $(this).find('.mins').html(event.strftime('%-M'));
        $(this).find('.mins-label').html(event.strftime('min%!M'));
        $(this).find('.secs').html(event.strftime('%-S'));
        $(this).find('.secs-label').html(event.strftime('sec%!S'));
      })
      .on('finish.countdown', function(event) {
        $(this).html('The event has expired!')
          .parent().addClass('disabled');
      });
    }
  };
})(jQuery, Drupal);