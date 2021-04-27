(function ($, Drupal, settings) {
  'use strict';

  /**
   * Webform Analaysis - Charts.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.WebformAnalysisCharts = {
    attach: function (context, settings) {

      var webformcharts = settings.webformcharts;

      google.charts.load('46', {packages: webformcharts.packages});

      google.charts.setOnLoadCallback(function () {

        var charts = $.map(webformcharts.charts, function (value, index) {
          return [value];
        });

        charts.forEach(function (chart) {
          var data = new google.visualization.arrayToDataTable(chart.data);
          var options = chart.options;
          var gchart = new google.visualization[chart.type](document.querySelector(chart.selector));
          gchart.draw(data, options);

        });
      });

    }
  };
})(jQuery, Drupal, drupalSettings);
