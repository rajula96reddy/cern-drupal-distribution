<?php

namespace Drupal\cern_cds_media\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;


/**
 * Plugin implementation of the 'CernCdsMediaDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "CernCdsMediaDefaultFormatter",
 *   label = @Translation("Cern CDS Media"),
 *   field_types = {
 *     "CernCdsMedia"
 *   }
 * )
 */
class CernCdsMediaDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   *
   * Inside this method we can customize how the field is displayed inside
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    foreach ($items as $delta => $item) {
      $markup = '';
      if (isset($item->{'caption_'.$language}) && !empty($item->{'caption_'.$language}) ) {
        $caption_field = 'caption_'.$language;
      } else {
        $caption_field = 'caption_en';
      }

      if ($item->type == 'image') {
        $markup = '<figure class="cds-image" data-record-id="'.$item->record_id.'" data-filename="'. $item->filename .'" id="'. $item->cds_id.'">
        <a href="//cds.cern.ch/images/'.$item->cds_id.'" 
          title="View on CDS">
          <img alt="'.$item->title_en.'" 
            src="//cds.cern.ch/images/'.$item->cds_id .'/file?size=' . (empty($item->size) ? 'small' : $item->size) .'"/>
        </a>
        <figcaption>
          '.$item->{$caption_field}.'
          <span> (Image: '.$item->license_body.')</span>
        </figcaption>
      </figure>';
      } elseif ($item->type == 'video' || $item->type == '360 video') {
        $query_params = [];
        $query = '';
        if($item->controls_off == 1){
          $query_params['controlsOff'] = 1;
        }
        if($item->autoplay == 1){
          $query_params['autoplay'] = 1;
        }
        if($item->loop == 1){
          $query_params['loop'] = 1;
        }
        if($item->subtitles_off == 1){
          $query_params['subtitlesOff'] = 1;
        }
        if($item->muted == 1){
          $query_params['muted'] = 1;
        }
        if($item->responsive == 1){
          $query_params['responsive'] = 1;
        }
        if($item->start > 0){
          $query_params['start'] = $item->start;
        }
        if($item->stop > 0){
          $query_params['end'] = $item->stop;
        }
        if (count($query_params) > 0) {
          $query = '?'. http_build_query($query_params);
        }
        $markup = '<figure class="cds-video" id="'. $item->cds_id . '" data-download-url="' . $item->download_url. '" data-download-sizes="' . $item->download_sizes.'">
          <div>
            <iframe allowfullscreen="true" frameborder="0" height="450" 
              src="//cds.cern.ch/video/'.$item->cds_id . $query.'" width="100%">
            </iframe>
          </div>
          <figcaption>
            '.$item->{$caption_field}.'
            <span> (Video: '.$item->license_body.')</span>
          </figcaption>
        </figure>';
      }
      $elements[$delta] = [
        '#type' => 'inline_template',
        '#template' => $markup
      ];
    }

    return $elements;
  }

} // class
