<?php

namespace Drupal\cern_webcast_feeds\Feeds\Parser;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\feeds\Exception\EmptyFeedException;
use Drupal\feeds\FeedInterface;
use Drupal\feeds\Feeds\Item\DynamicItem;
use Drupal\feeds\Plugin\Type\Parser\ParserInterface;
use Drupal\feeds\Plugin\Type\PluginBase;
use Drupal\feeds\Result\FetcherResultInterface;
use Drupal\feeds\Result\ParserResult;
use Drupal\feeds\StateInterface;

/**
 * Defines an Webcast feed parser.
 *
 * @FeedsParser(
 *   id = "webcast",
 *   title = @Translation("Webcast"),
 *   description = @Translation("Parse Webcast events.")
 * )
 */
class WebcastParser extends PluginBase implements ParserInterface {

  /**
   * {@inheritdoc}
   */
  public function parse(FeedInterface $feed, FetcherResultInterface $fetcher_result, StateInterface $state) {
    $raw = $fetcher_result->getRaw();
    if (!strlen(trim($raw))) {
      throw new EmptyFeedException();
    }

    $result = new ParserResult();

    $data = json_decode($raw);
    foreach ($this->getItems($data->results) as $item) {
      $result->addItem($item);
    }

    return $result;
  }

  /**
   * Returns a flattened array of feed items.
   *
   * @param array $events
   *   An array of events.
   *
   * @return array
   *   The flattened list of feed items.
   */
  protected function getItems(array $events) {
    $items = [];
    foreach ($events as $event) {
      $item = new DynamicItem();
      $item->set('abstract', $event->abstract);
      $item->set('embedLinkCamera', $event->embed_link_camera);
      $item->set('embedLinkSlides', $event->embed_link_slides);
      $item->set('endDateTime', $event->end_date);
      $item->set('eventLink', $event->event_link);
      $item->set('iCal', $event->ical_link);
      $item->set('id', $event->id);
      $item->set('image', $event->img);
      $item->set('category', $event->indico_category);
      $item->set('categoryId', $event->indico_id);
      $item->set('restricted', $event->restricted == 'true' ? 1 : 0);
      $item->set('speakers', $event->speakers);
      $item->set('room', $event->room_name);
      $item->set('startDateTime', $event->start_date);
      $item->set('title', strip_tags($event->title));
      $item->set('timezone', $event->timezone);
      $item->set('type', $event->type);
      $item->set('webcastLink', $event->webcast_link);

      $items[] = $item;
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getMappingSources() {
    return [
      'abstract' => [
        'label' => $this->t('Abstract'),
        'description' => $this->t('Abstract text of the event.'),
      ],
      'embedLinkCamera' => [
        'label' => $this->t('Embed link camera'),
        'description' => $this->t('Embed Link Camera of the event'),
      ],
      'embedLinkSlides' => [
        'label' => $this->t('Embed link slides'),
        'description' => $this->t('Embed Link Slides of the event'),
      ],
      'endDateTime' => [
        'label' => $this->t('End date/time'),
        'description' => $this->t('End date/time of the event'),
      ],
      'eventLink' => [
        'label' => $this->t('Indico link'),
        'description' => $this->t('Indico link of the event'),
      ],
      'iCal' => [
        'label' => $this->t('iCal link'),
        'description' => $this->t('Indico iCal link of the event'),
      ],
      'id' => [
        'label' => $this->t('Event ID'),
        'description' => $this->t('Unique ID of the event.'),
      ],
      'image' => [
        'label' => $this->t('Image'),
        'description' => $this->t('Image of the event'),
      ],
      'category' => [
        'label' => $this->t('Category'),
        'description' => $this->t('Indico category of the event'),
      ],
      'categoryId' => [
        'label' => $this->t('Category ID'),
        'description' => $this->t('Indico category ID of the event'),
      ],
      'restricted' => [
        'label' => $this->t('Restricted'),
        'description' => $this->t('Restricted event'),
      ],
      'speakers' => [
        'label' => $this->t('Speakers'),
        'description' => $this->t('Speakers of the event'),
      ],
      'room' => [
        'label' => $this->t('Room'),
        'description' => $this->t('Room of the event'),
      ],
      'startDateTime' => [
        'label' => $this->t('Start date/time'),
        'description' => $this->t('Start date/time of the event'),
      ],
      'title' => [
        'label' => $this->t('Title'),
        'description' => $this->t('Title of the event'),
      ],
      'timezone' => [
        'label' => $this->t('Timezone'),
        'description' => $this->t('Timezone of the event'),
      ],
      'type' => [
        'label' => $this->t('Type'),
        'description' => $this->t('Type of the event'),
      ],
      'location' => [
        'label' => $this->t('Location'),
        'description' => $this->t('Location of the event'),
      ],
      'webcastLink' => [
        'label' => $this->t('Webcast link'),
        'description' => $this->t('Webcast link of the event'),
      ],
    ];
  }
}
