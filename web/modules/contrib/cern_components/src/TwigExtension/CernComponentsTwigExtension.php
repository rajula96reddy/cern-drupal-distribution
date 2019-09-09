<?php

namespace Drupal\cern_components\TwigExtension;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Link;
use Drupal\Core\Menu\MenuLinkTree;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Url;
use Drupal\Component\Utility\Xss;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\Entity\View;
use Drupal\views\Views;
use Drupal\viewsreference\Plugin\Field\FieldType\ViewsReferenceItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;

/**
 * Class CernComponentsTwigExtension.
 */
class CernComponentsTwigExtension extends \Twig_Extension {

  /**
   * Used for getting $_GET parameters.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * CernComponentsTwigExtension constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   Drupal::request()
   */
  public function __construct(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'cern_components';
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('openlink', [$this, 'openLink']),
      new \Twig_SimpleFilter('closelink', [$this, 'closeLink']),
      new \Twig_SimpleFilter('first_char', [$this, 'firstChar']),
      new \Twig_SimpleFilter('get_img_alt', [$this, 'getImgAlt']),
      new \Twig_SimpleFilter('get_img_src', [$this, 'getImgSrc']),
      new \Twig_SimpleFilter('get_link_href', [$this, 'getLinkHref']),
      new \Twig_SimpleFilter('get_link_type', [$this, 'getLinkType']),
      new \Twig_SimpleFilter('md5', [$this, 'md5']),
      new \Twig_SimpleFilter('format_date_field', [$this, 'formatFieldDate']),
      new \Twig_SimpleFilter('field_count', [$this, 'fieldCount']),
      new \Twig_SimpleFilter('get_rgba', [$this, 'getRgba']),
      new \Twig_SimpleFilter('iterate_element', [$this,'iterateElement']),
      new \Twig_SimpleFilter('get_language_message', [$this,'getLanguageMessage']),
      new \Twig_SimpleFilter('iterate_referenced_entities', [$this,'iterateReferencedEntities']),
      new \Twig_SimpleFilter('render_plugin_block', [$this,'renderPluginBlock']),
      new \Twig_SimpleFilter('render_this', [$this,'renderThis']),
      new \Twig_SimpleFilter('render_paragraph_field', [$this,'renderParagraphField']),
      new \Twig_SimpleFilter('get_cds_info', [$this,'getCdsInfo']),
      new \Twig_SimpleFilter('get_tag_attribute', [$this,'getTagAttribute']),
      new \Twig_SimpleFilter('set_tag_attribute', [$this,'setTagAttribute']),
      new \Twig_SimpleFilter('get_path', [$this,'getPath']),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('openlink', [$this, 'openLink']),
      new \Twig_SimpleFunction('closelink', [$this, 'closeLink']),
      new \Twig_SimpleFunction('first_char', [$this, 'firstChar']),
      new \Twig_SimpleFunction('get_img_alt', [$this, 'getImgAlt']),
      new \Twig_SimpleFunction('get_img_src', [$this, 'getImgSrc']),
      new \Twig_SimpleFunction('get_link_href', [$this, 'getLinkHref']),
      new \Twig_SimpleFunction('get_link_type', [$this, 'getLinkType']),
      new \Twig_SimpleFunction('get_param', [$this, 'getParam']),
      new \Twig_SimpleFunction('md5', [$this, 'md5']),
      new \Twig_SimpleFunction('format_date_field', [$this, 'formatFieldDate']),
      new \Twig_SimpleFunction('field_count', [$this, 'fieldCount']),
      new \Twig_SimpleFunction('get_rgba', [$this, 'getRgba']),
      new \Twig_SimpleFunction('iterate_element', [$this,'iterateElement']),
      new \Twig_SimpleFunction('get_language', [$this,'getLanguageMessage']),
      new \Twig_SimpleFunction('iterate_referenced_entities', [$this,'iterateReferencedEntities']),
      new \Twig_SimpleFunction('render_this', [$this,'renderThis']),
      new \Twig_SimpleFunction('render_paragraph_field', [$this,'renderParagraphField']),
      new \Twig_SimpleFunction('render_plugin_block', [$this,'renderPluginBlock']),
      new \Twig_SimpleFunction('get_cds_info', [$this,'getCdsInfo']),
      new \Twig_SimpleFunction('get_tag_attribute', [$this,'getTagAttribute']),
      new \Twig_SimpleFunction('set_tag_attribute', [$this,'setTagAttribute']),
      new \Twig_SimpleFilter('get_path', [$this,'getPath']),
    ];
  }

  public function formatFieldDate($field, $type, $format = '') {
    $field = reset($field);
    if (isset($field['#items'])) {
      /** @var $items \Drupal\datetime\Plugin\Field\FieldType\DateTimeFieldItemList */
      $items = $field['#items'];
      $value = $items->get(0)->getValue();
      if (isset($value['value']) && !empty($value['value'])) {
        $initial_format = mb_strlen($value['value']) > 10 ? DATETIME_DATETIME_STORAGE_FORMAT : 'Y-m-d';
        $value = \DateTime::createFromFormat($initial_format, $value['value'], new \DateTimeZone('UTC'))->format('U');
        return ['#markup' => \Drupal::service('date.formatter')->format($value, $type, $format)];
      }
    }
  }

  /**
   * Build a link in a basic render array without the closing <a> tag.
   *
   * @param array|string $original_link
   *   Render array of a link field, url, or link html.
   * @param array $attributes
   *   Key value pair of attributes to set on the link.
   *
   * @return array|null
   *   Render array or null if no link.
   */
  public function openLink($original_link, $attributes = []) {
    // Make sure we have a string.
    $original_link = render($original_link);
    $trimmed = trim(strip_tags($original_link));

    // Empty.
    if (!$trimmed) {
      return NULL;
    }

    $dom = new \DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($original_link);

    // Get all links.
    /** @var \DOMNodeList $link */
    $link = $dom->getElementsByTagName('a');
    if ($link->length) {
      /** @var \DOMElement $link_element */
      $link_element = $link->item(0);

      // Set title attribute if none exits.
      if (!$link_element->getAttribute('title') && !isset($attributes['title'])) {
        $link_element->setAttribute('title', $trimmed);
      }

      // Append attributes to retain field settings.
      foreach ($attributes as $key => $value) {
        $existing_attribute = $link_element->getAttribute($key);
        $existing_attribute .= " $value";
        $link_element->setAttribute($key, trim($existing_attribute));
      }

      // Get html.
      $link_html = $dom->saveHTML($link_element);
    }
    else {
      if (!isset($attributes['title'])) {
        $attributes['title'] = $trimmed;
      }

      if (substr($trimmed, 0, 1) == '/') {
        global $base_url;
        $trimmed = $base_url . $trimmed;
      }
      $url = Url::fromUri($trimmed);
      $url->setOptions(['attributes' => $attributes]);
      $link_html = Link::fromTextAndUrl(strip_tags($original_link), $url)
        ->toString()
        ->getGeneratedLink();
    }

    return ['#markup' => substr($link_html, 0, strpos($link_html, '>') + 1)];
  }

  /**
   * Gets the closing tag for the link.
   *
   * @param mixed $original_link
   *   Render array, string or null.
   *
   * @return array|null
   *   Render array of the closing <a> tag.
   */
  public function closeLink($original_link) {
    return $this->openLink($original_link) ? ['#markup' => '</a>'] : NULL;
  }

  /**
   * Gets the very first letter of the item.
   *
   * @param mixed $item
   *   Render array, string, or null.
   *
   * @return array|null
   *   Render array with the drop cap letter or null if no letter is there.
   */
  public function firstChar($item) {
    $item = render($item);
    $firstchar = substr(trim(strip_tags($item)), 0, 1);
    return $firstchar ? $firstchar : NULL;
  }

  /**
   * Parse the item to get the image alt text.
   *
   * @param mixed $item
   *   Render array or string of the image alt text.
   *
   * @return null|string
   *   Alt text.
   */
  public function getImgAlt($item) {
    $alt = $this->getTagAttribute(render($item), 'img', 'alt');
    if (!$alt) {
      $alt = trim(strip_tags(render($item)));
    }
    return $alt ? $alt : NULL;
  }

  /**
   * Parse the item to get an actual image url.
   *
   * @param mixed $item
   *   Render array or string of the image field/url.
   *
   * @return null|string
   *   Url to image.
   */
  public function getImgSrc($item) {
    $src = $this->getTagAttribute(render($item), 'img', 'src');
    if (!$src) {
      $src = trim(strip_tags(render($item)));
    }
    return $src ? $src : NULL;
  }

  /**
   * Parse the item to get an actual link href.
   *
   * @param mixed $item
   *   Render array or string of the link field/url.
   *
   * @return null|string
   *   Url to link.
   */
  public function getLinkHref($item) {
    $href = $this->getTagAttribute(render($item), 'a', 'href');
    if (!$href) {
      $href = trim(strip_tags(render($item)));
    }
    return $href ? $href : NULL;
  }

  /**
   * Parse the item to get an actual link url.
   *
   * @param mixed $item
   *   Render array or string of the link field/url.
   *
   * @return null|string
   *   URL type.
   */
  public function getLinkType($item) {
    $type = $this->getTagAttribute(render($item), 'a', 'type');
    if (!$type) {
      $type = trim(strip_tags(render($item)));
    }
    return $type ? $type : NULL;
  }

  /**
   * Get a specific attribute from a specific element.
   *
   * @param string $html
   *   The html containing the tag.
   * @param string $tag
   *   The element tag name.
   * @param string $attribute
   *   The attribute to get from the tag.
   * @param int $index
   *   The index of the element if the first is to be ignored.
   *
   * @return null|string
   *   The attribute value or null if none there.
   */
  public function getTagAttribute($html, $tag, $attribute, $index = 0) {
    // Validate before trying to parse.
    if (!$html) {
      return NULL;
    }

    $dom = new \DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($html);
    /** @var \DOMNodeList $items */
    $items = $dom->getElementsByTagName($tag);

    // No items of that tag or index.
    if (!$items || !$items->item($index)) {
      return NULL;
    }

    return $items->item($index)->getAttribute($attribute);
  }

  /**
   *
   */
  public function setTagAttribute($item) {
    $html = $finalIframe = render($item);
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML($html);
    $xpath = new \DOMXPath($dom);
    //$nodes = $xpath->query("//iframe[contains(concat(' ', normalize-space(@rel), ' '), ' external ')]");
    $nodes = $dom->getElementsByTagName('iframe');
    foreach($nodes as $node) {
        $firstParam = (strpos($node->getAttribute('src'), '?')) ? '&' : '?';
        $node->setAttribute('src', $node->getAttribute('src').$firstParam.'autoplay=1&controlsOff=1&loop=1&muted=1&responsive=1&subtitlesOff=1');
    }
    $finalIframe = $dom->saveHTML();
    return $finalIframe;
  }




  /**
   * Get parameter from $_GET.
   *
   * @param string $name
   *   Parameter key.
   * @param mixed $default
   *   The default value if the parameter key does not exist.
   * @param bool $deep
   *   If true, a path like foo[bar] will find deeper items.
   *
   * @return mixed|null
   *   Value of the parameter or null if nothing.
   */
  public function getParam($name, $default = NULL, $deep = FALSE) {
    $value = $this->requestStack->getCurrentRequest()->query->get($name, $default, $deep);
    return Xss::filter($value);
  }

  /**
   * Get an md5 of an item.
   *
   * @param mixed $item
   *   Something to get an md5 hash for.
   *
   * @return string
   *   Md5 of serialized item.
   */
  public function md5($item) {
    return md5(serialize($item));
  }

  /**
   * Since there's no way to get number of field items in twig, we do this.
   *
   * @param mixed $item
   *   Field item render array.
   *
   * @return int
   *   Number of field items.
   */
  public function fieldCount($item) {
    // Only 1 field in the region.
    if (count($item) == 1 && strpos(key($item), "field_") === 0) {
      $field_key = key($item);
      if (is_array($item[$field_key]) && isset($item[$field_key]['#items'])) {
        $items = $item[$field_key]['#items'];
        return $items->count();
      }
    }
    // The item is the field render array.
    elseif (is_array($item) && isset($item['#items'])) {
      /** @var \Drupal\Core\Field\FieldItemList $items */
      $items = $item['#items'];
      return $items->count();
    }

    return count($item);
  }

  /**
   * Transfor hexadecimal color into rgba color.
   *
   * @param int $color
   *   Hexadecimal color (value).
   * @param int $opacity
   *   Opacity (value).
   *
   * @return string
   *   RGB and opacity css value.
   */
  public function getRgba($color, $opacity = false) {
    $default = 'auto';

    if (empty($color))
        return $default;

    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);

    elseif (strlen($color) == 3)
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);

    else
        return $default;

    $rgb = array_map('hexdec', $hex);

    if ($opacity) {
        if (abs($opacity) > 1)
            $opacity = 1.0;

        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }
    return $output;
  }


  /**
   * Iterate a collection.
   *
   * @param mixed element
   *   Element to iterate (collection).
   *
   * @return array
   *   Return each item inside the collection (image, file...).
   */
  public function iterateElement($element) {
    $useful_element = [];
    foreach ( $element as $element1 ) {
      foreach ($element1 as $key => $value) {
        if (is_numeric($key)) {
          array_push($useful_element, $value);
        }
      }
    }
    return $useful_element;
  }

    /**
     * This function will return the entities referenced by the entity reference
     * @param $element
     * @return mixed
     */
  public function iterateReferencedEntities($element) {
      return $element->referencedEntities();
  }

    /**
     * This function will return the render array of a field inside a paragraph
     *
     * @param Paragraph $paragraph
     * @param $field_name
     * @param array $options
     * @return array
     */
  public function renderParagraphField(Paragraph $paragraph, $field_name, $options = []) {
    if (!isset($options['view_mode'])) {
        $options['view_mode'] = 'default';
    }
    $fields = $paragraph->get($field_name);
    $referenced_entities = $fields->referencedEntities();
    $markup = [];
    foreach($referenced_entities as $key => $referenced_entity) {
      if ($referenced_entity instanceof View) {
        $field = $fields[$key];
        $options['target_id'] = $field->get('target_id')->getValue();
        $options['display_id'] = $field->get('display_id')->getValue();
        $options['data'] = unserialize($field->get('data')->getValue());

      }
      $markup[] = self::renderThis($referenced_entity, $options['view_mode'], $options);
    }

    return $markup;
  }

  public static function renderPluginBlock($block_id) {
    $block_manager = \Drupal::service('plugin.manager.block');
    // You can hard code configuration or you load from settings.
    $config = [];
    $plugin_block = $block_manager->createInstance($block_id, $config);
    // Some blocks might implement access check.
    $access_result = $plugin_block->access(\Drupal::currentUser());
    // Return empty render array if user doesn't have access.
    // $access_result can be boolean or an AccessResult class
    if (is_object($access_result) && $access_result->isForbidden() || is_bool($access_result) && !$access_result) {
      // You might need to add some cache tags/contexts.
      return [];
    }
    $render = $plugin_block->build();
    // In some cases, you need to add the cache tags/context depending on
    // the block implemention. As it's possible to add the cache tags and
    // contexts in the render method and in ::getCacheTags and
    // ::getCacheContexts methods.
    return $render;
  }
    /**
     * Returns the rendered array for a single entity field.
     *
     * @param object $content
     *   Entity or Field object.
     * @param string $view_mode
     *   Name of the display mode.
     * @param
     *
     * @return null|array
     *   A rendered array for the field or NULL if the value does not exist.
     */
    public static function renderThis($content, $view_mode = 'default', $options = []) {
        if ($content instanceof MenuLinkContent) {
            $root_menu_item = $content;
            $menu_parameters = new MenuTreeParameters();
            $menu_parameters->setMaxDepth($options['max_depth']);
            $menu_parameters->setRoot($root_menu_item->getPluginId());

            /** @var MenuLinkTree $menu_tree_service */
            $menu_tree_service = \Drupal::service('menu.link_tree');
            $tree = $menu_tree_service->load('main-menu', $menu_parameters);
            // Apply some manipulators (checking the access, sorting).
            $manipulators = [
                ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
                ['callable' => 'menu.default_tree_manipulators:checkAccess'],
                ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
            ];
            $tree = $menu_tree_service->transform($tree, $manipulators);
            // And the last step is to actually build the tree.
            return $menu_tree_service->build($tree);
        } elseif ($content instanceof View) {
            $view = Views::getView($options['target_id']);
            $view->setArguments([$options['data']['argument']]);
            $view->setDisplay($options['display_id']);
            $view->preExecute();
            $view->execute();
            return $view->buildRenderable($options['display_id']);
        } elseif ($content instanceof EntityInterface) {
            $view_builder = \Drupal::entityTypeManager()
                ->getViewBuilder($content->getEntityTypeId());
            return $view_builder->view($content, $view_mode);
        }
        elseif ($content instanceof FieldItemInterface ||
            $content instanceof FieldItemListInterface ||
            method_exists($content, 'view')
        ) {
            return $content->view($view_mode);
        }
        else {
            return t('Twig Filter: Unsupported content.');
        }
    }



    /**
   * Return the sentence Voir in Français / View in English.
   *
   * @return array
   *   Return sentence with the markup.
   */
  public function getLanguageMessage() {
    // Sentence to print
    $sentence = null;
    // Current language Code
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    // Current node
    $node = \Drupal::routeMatch()->getParameter('node');
    // if $node is object (for nodes)
    if ($node instanceof \Drupal\node\NodeInterface) {
      $languages = $node->getTranslationLanguages();
      // Get node nid
      $nid = $node->id();
    // if $node is string (for revisions)
    } else if (is_string($node)) {
      // Get node nid
      $node = node_load($node);
    }
    if (isset($node)) {
      if ($language == 'en' && $node->hasTranslation('fr') && $node->hasTranslation('en')) {
        $node_url = $node->getTranslation('fr')->toUrl()->toString();
        $sentence = array(
          '#markup' => '<p>Voir en</p> <a href="' . $node_url . '">français</a>',
        );
      }
      else if ($language == 'fr' && $node->hasTranslation('en') && $node->hasTranslation('fr')) {
        $node_url = $node->getTranslation('en')->toUrl()->toString();
        $sentence = array(
          '#markup' => '<p>View in</p> <a href="' . $node_url . '">English</a>',
        );
      }
    }
    return $sentence;
  }


  /**
   * Return info from CDS paragraph.
   *
   * @return array
   *   Return string with info.
   */
  public function getCdsInfo($paragraph) {
    // Get paragraph id
    $paragraph_id = $paragraph['field_p_resource_display_gallery'][0]['#paragraph']->id();
    // Get paragraph object
    $paragraph_object = Paragraph::load($paragraph_id);
    // More info from CDS
    $info = $paragraph_object->getFields()['field_p_gallery_cds_image']->getValue();

    return($info);
  }

  /**
   * Get path url
   *
   * @param mixed $item
   *
   * @return null|string
   *   URL type.
   */
  public function getPath($url) {
    $path = substr($url, 0, strrpos($url, "/"));

    return $path ? $path : NULL;
  }
}
