<?php

namespace Drupal\cern_cds_media\Plugin\Field\FieldType;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use GuzzleHttp\Exception\RequestException;
use \Drupal\Component\Utility\Unicode;

/**
 * Plugin implementation of the 'address' field type.
 *
 * @FieldType(
 *   id = "CernCdsMedia",
 *   label = @Translation("CERN CDS Media"),
 *   description = @Translation("Stores a reference to CERN CDS item."),
 *   category = @Translation("CERN"),
 *   default_widget = "CernCdsMediaDefaultWidget",
 *   default_formatter = "CernCdsMediaDefaultFormatter"
 * )
 */
class CernCdsMedia extends FieldItemBase {
  public static $metadata_string = [
    'cds_id',
    'attribution',
    'copyright_date',
    'copyright_holder',
    'creation_date',
    'entry_date',
    'filename',
    'keywords',
    'license_body',
    'license_desc',
    'license_url',
    'record_id',
    'title_en',
    'title_en',
    'type',
    'size',
    'download_url',
    'download_sizes',
  ];

  public static $metadata_boolean = [
    'autoplay',
    'muted',
    'loop',
    'controls_off',
    'subtitles_off',
    'responsive'
  ];

  public static $metadata_integer = [
    'start',
    'stop',
  ];

  public static $metadata_text = [
    'caption_en',
    'caption_fr',
  ];

  /**
   * Field type properties definition.
   *
   * Inside this method we defines all the fields (properties) that our
   * custom field type will have.
   *
   * Here there is a list of allowed property types: https://goo.gl/sIBBgO
   */
  public static function propertyDefinitions(StorageDefinition $storage) {

    foreach (self::$metadata_string as $string_property) {
      $properties[$string_property] = DataDefinition::create('string')
        ->setLabel(t(ucwords(str_replace('_', ' ', $string_property))));
    }

    foreach (self::$metadata_boolean as $boolean_property) {
      $properties[$boolean_property] = DataDefinition::create('boolean')
        ->setLabel(t(ucwords(str_replace('_', ' ', $boolean_property))));
    }

    foreach (self::$metadata_text as $text_property) {
      $properties[$text_property] = DataDefinition::create('string')
        ->setLabel(t(ucwords(str_replace('_', ' ', $text_property))));
    }

    foreach (self::$metadata_integer as $integer_property) {
      $properties[$integer_property] = DataDefinition::create('integer')
        ->setLabel(t(ucwords(str_replace('_', ' ', $integer_property))));
    }

    return $properties;
  }

  /**
   * Field type schema definition.
   *
   * Inside this method we defines the database schema used to store data for
   * our field type.
   *
   * Here there is a list of allowed column types: https://goo.gl/YY3G7s
   */
  public static function schema(StorageDefinition $storage) {
    foreach (self::$metadata_string as $column) {
      $columns[$column] = [
        'type' => 'char',
        'length' => 255,
      ];
    }

    foreach (self::$metadata_text as $column) {
      $columns[$column] = [
        'type' => 'text',
        'size' => 'medium',
      ];
    }

    foreach (self::$metadata_boolean as $column) {
      $columns[$column] = [
        'type' => 'int',
        'size' => 'tiny',
      ];
    }

    foreach (self::$metadata_integer as $column) {
      $columns[$column] = [
        'type' => 'int',
        'size' => 'normal',
      ];
    }

    return [
      'columns' => $columns,
      'indexes' => [],
    ];
  }


  /**
   * @inheritdoc
   */
  public function preSave(){
    if(isset($this->cern_cds_fieldset)) {
      foreach($this->cern_cds_fieldset as $property => $value){
        if ($value != '') {
          $value = !in_array($property, self::$metadata_text)
            ? Unicode::truncate($value, 255)
            : $value;
        }
        $this->{$property} = $value;
      }
    }
  }

  /**
   * Define when the field type is empty.
   *
   * This method is important and used internally by Drupal. Take a moment
   * to define when the field fype must be considered empty.
   */
  public function isEmpty() {

    $isEmpty = empty($this->get('cds_id')->getValue());
    return $isEmpty;
  }

} // class
