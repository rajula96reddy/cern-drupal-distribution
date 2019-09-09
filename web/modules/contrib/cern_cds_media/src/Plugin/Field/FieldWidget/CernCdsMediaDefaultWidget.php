<?php
namespace Drupal\cern_cds_media\Plugin\Field\FieldWidget;
use Drupal;
use Drupal\cern_cds_media\Plugin\Field\FieldType\CernCdsMedia;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\cern_cds_media\Plugin\Field\FieldType;

/**
 * Plugin implementation of the 'CernCdsMediaDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "CernCdsMediaDefaultWidget",
 *   label = @Translation("CERN CDS Media"),
 *   field_types = {
 *     "CernCdsMedia"
 *   }
 * )
 */
class CernCdsMediaDefaultWidget extends WidgetBase {
  /**
   * Define the form for the field type.
   *
   * Inside this method we can define the form used to edit the field type.
   *
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta,
    Array $element,
    Array &$form,
    FormStateInterface $formState
  ) {

    foreach(array_merge(CernCdsMedia::$metadata_text, CernCdsMedia::$metadata_string) as $property) {
      $element[$property] = [
        '#type' => 'textfield',
        '#title' => t(ucwords(str_replace('_', ' ', $property))),
        '#default_value' => isset($items[$delta]->{$property}) ?
          $items[$delta]->{$property} : null,
        '#empty_value' => '',
        '#maxlength' => 255,
      ];
    }

    foreach(CernCdsMedia::$metadata_boolean as $property) {
      $element[$property] = [
        '#type' => 'checkbox',
        '#title' => t(ucwords(str_replace('_', ' ', $property))),
        '#return_value' => 1,
        '#default_value' => isset($items[$delta]->{$property}) ?
          $items[$delta]->{$property} : null,
        '#attributes' => ['class' => ['video-field']]
      ];
    }

    foreach(CernCdsMedia::$metadata_integer as $property) {
      $element[$property] = [
        '#type' => 'number',
        '#title' => t(ucwords(str_replace('_', ' ', $property))),
        '#default_value' => isset($items[$delta]->{$property}) ?
          $items[$delta]->{$property} : null,
        '#size' => 6,
        '#attributes' => ['class' => ['video-field']]
      ];
    }

    $element['cds_id']['#title'] = t('CDS ID');
    $element['cds_id']['#placeholder'] = t('Example: CERN-PHOTO-201405-097-1');
    $element['cds_id']['#element_validate'] = [[static::class, 'validate']];
    $element['cds_id']['#attributes']['data-delta'] = $delta;
    $element['cds_id']['#attached']['library'] = ['cern_cds_media/cern-cds-media'];
    $element['record_id']['#attributes']['readonly'] = 'readonly';
    $element['type']['#attributes']['readonly'] = 'readonly';
    $element['cds_id']['#attributes']['data-delta'] = $delta;
    $element['size']['#type'] = 'hidden';
    $element['size']['#default_value'] = isset($items[$delta]->size) ? $items[$delta]->size : 'small';
    $element['download_url']['#attributes'] = ['class' => ['video-field']];
    $element['download_sizes']['#attributes'] = ['class' => ['video-field']];

    $fieldset['cds_id'] = NULL;
    $fieldset['cern_cds_fieldset'] = [
      '#type' => 'details',
      '#title' => t('Metadata'),
      '#description' => t('CDS Resource metadata'),
      '#open' => FALSE,
    ];

    foreach ($element as $property => $config) {
      $fieldset['cern_cds_fieldset'][$property] = $config;
    }
    $fieldset['cds_id'] = $fieldset['cern_cds_fieldset']['cds_id'];
    unset($fieldset['cern_cds_fieldset']['cds_id']);
    $element = $fieldset;

    return $element;
  }

  /**
   * Validate the CDS ID text field.
   */
  public static function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (empty($value)) {
      $form_state->setValueForElement($element, '');
      return;
    } else {
      $parents = $element['#parents'];
      array_pop($parents);
      $cds_values = NestedArray::getValue($form_state->getValues(), $parents);
      $record_id = $cds_values['cern_cds_fieldset']['record_id'];
      if (empty($record_id)) {
        $form_state->setError($element, t('Please click on "Get resource" button to retrieve the CDS information for ID: %id', ['%id' => $value]));
      }
    }
  }
} // class
