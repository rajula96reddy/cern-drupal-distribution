(function ($, Drupal) {
  'use strict';

  /**
   * Attaches the CERN CDS Media behavior .
   */
  Drupal.behaviors.cernCdsMedia = {
    attach: function (context, settings) {

      $('[data-drupal-selector^=edit-field-][data-drupal-selector$=-cds-id]', context).once().after(function () {
        var delta = $(this).data('delta');
        var drupal_selector = $(this).data('drupal-selector');
        var field_selector = drupal_selector.split('-cds-id')[0];
        if ($('[data-drupal-selector^=' + field_selector + '][data-drupal-selector$=-record-id]', context).val() != '') {
          var resource_type = $('[data-drupal-selector^=' + field_selector + '][data-drupal-selector$=-cern-cds-fieldset-type]', context).val();
          var title = $('[data-drupal-selector^=' + field_selector + '][data-drupal-selector$=-title-en]', context).val();
          var size = $('[data-drupal-selector^=' + field_selector + '][data-drupal-selector$=-size]', context).val();
          Drupal.behaviors.cernCdsMedia.preview(field_selector, $(this).val(), title, resource_type, size);
        }
        var remove_button;
        remove_button = '<a class="cds-button-remove button button--primary" data-field-selector="' + field_selector + '" data-delta="' + delta + '">Remove</a>';

        return '<a class="cds-button button button--primary" data-field-selector="' + field_selector + '" data-delta="' + delta + '">Get resource</a>' + remove_button;
      });

      $('.cds-button-remove', context).once('removeResource').on('click', function () {
        Drupal.behaviors.cernCdsMedia.cleanCDSField(this, context);
      });

      $('.cds-button', context).once().on('click', function () {
        var field_selector = $(this).data('field-selector');
        var cds_id = $('[id^="' + field_selector + '"][id*="-cds-id"]').val();
        var absolute_url = 'https://cds.cern.ch/api/mediaexport?id=' + cds_id;
        if (cds_id !== '') {
          $.ajax({
            dataType: "json",
            url: absolute_url,
            async: true,
            success: function (jsondata) {
              var cdsdata = jsondata.entries[0].entry;
              if (!$.isEmptyObject(cdsdata)) {
                $.each(cdsdata, function (key, data) {
                  if (typeof data == 'string' && data!='') {
                    $('[id^=' + field_selector + '][id*=-cern-cds-fieldset-' + (key.replace('_', '-') + ']'), context).val(data);
                  }
                  if (key == 'links' && typeof data == 'object' && data!='') {
                    var download_sizes = [];
                    $.each(data, function (key2, data2) {
                      if (key2 == 'original') {
                        //var n = data2.lastIndexOf("/");
                        //$('[id^=' + field_selector + '][id*=-cern-cds-fieldset-download-url]', context).val(data2.substr(0,n));
                        $('[id^=' + field_selector + '][id*=-cern-cds-fieldset-download-url]', context).val(data2);
                        return false;
                      }
                      download_sizes.push(key2);
                    });
                    // Sizes download order by desc
                    download_sizes.sort(function(a, b){return parseInt(a)-parseInt(b)});
                    $('[id^=' + field_selector + '][id*=-cern-cds-fieldset-download-sizes]', context).val(download_sizes.toString());
                  }
                });
                Drupal.behaviors.cernCdsMedia.preview(field_selector, cds_id, cdsdata.title_en, cdsdata.type);
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              window.alert(Drupal.t('CDS resource not recognised. Note that resource IDs relate to a specific video or image and are not the same as a record ID (which can contain several resources).'));
            },
          });
        } else {
          window.alert(Drupal.t('Please enter a CDS resource ID.'));
        }
      });
    },
    cleanCDSField: function(button, context) {
      var field_selector = $(button).data('field-selector');
      $('[id^=' + field_selector + '][id*=-cds-id]', context).val('');
      $('[id^=' + field_selector + '][id*=-cern-cds-fieldset]', context).find('input').val('');
      $('[id^=' + field_selector + '][id*=-cern-cds-fieldset]', context).find('input').removeAttr('checked');
      $('.preview-' + field_selector).remove();
    },
    preview: function (drupal_selector, cds_id, title, resource_type, size) {
      var preview;
      size = size || 'small';
      if (resource_type == 'video' || resource_type == '360 video') {
        $('[data-drupal-selector^=' + drupal_selector + '][class~="video-field"]').parent().show();
        preview = '<iframe width="320" height="180" frameborder="0" src="//cds.cern.ch/video/' + cds_id + '?showTitle=true"></iframe>';
      } else if (resource_type == 'image') {
        $('[data-drupal-selector^=' + drupal_selector + '][class~="video-field"]').parent().hide();
        preview = '<img id="preview-image-' + drupal_selector + '" src="//cds.cern.ch/images/' + cds_id + '/file?size=small" title="' + title + '">' +
          '<br>' +
          '<select id="image-size-' + drupal_selector + '" ' +
          'data-drupal-selector="' + drupal_selector + '" ' +
          'data-preview-image="preview-image-' + drupal_selector + '">' +
          '<option value="small">Small</option>' +
          '<option value="medium">Medium</option>' +
          '<option value="large">Large</option>' +
          '</select>';
      }
      if (preview != '') {
        preview = '<div class="preview-' + drupal_selector + '">' +
          '<label>' + Drupal.t('Preview') + '</label>' +
          preview +
          '</div>';
        $('.preview-' + drupal_selector).remove();
        $('[id^=' + drupal_selector + ']' + '[id*=-cds-id]').parent().before(preview);
        $('select[id^="image-size"]').once().on('change', function () {
          $('[data-drupal-selector^=' + drupal_selector + '][data-drupal-selector$=-size]').val($(this).val());
        });
        $('#image-size-' + drupal_selector).val(size);
      }
    }
  };
})(jQuery, Drupal);
