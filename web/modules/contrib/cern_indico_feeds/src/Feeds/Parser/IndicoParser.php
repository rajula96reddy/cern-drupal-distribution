<?php

namespace Drupal\cern_indico_feeds\Feeds\Parser;

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
 * Defines an Indico feed parser.
 *
 * @FeedsParser(
 *   id = "indico",
 *   title = @Translation("Indico"),
 *   description = @Translation("Parse Indico events.")
 * )
 */
class IndicoParser extends PluginBase implements ParserInterface {

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
      $startDT = DrupalDateTime::createFromFormat('Y-m-d H:i:s', $event->startDate->date . ' ' . $event->startDate->time, $event->startDate->tz);
      $endDT = DrupalDateTime::createFromFormat('Y-m-d H:i:s', $event->endDate->date . ' ' . $event->endDate->time, $event->endDate->tz);
      $chairs = [];
      foreach ($event->chairs as $subarr) {
        $tmparr = explode(",", $subarr->fullName);
        if (count($tmparr) > 1) {
          $titles = "/Mr.|Ms.|Mrs.|Dr.|Prof./";
          if (preg_match($titles, $tmparr[0], $matches)) {
            $tmparr[0] = preg_replace($titles, "", $tmparr[0]);
            $tmparr[1] = $matches[0] . " " . $tmparr[1];
          }
          $chairs[] = trim($tmparr[1]) . " " . trim($tmparr[0]);
        }
        else {
          $chairs[] = $subarr->fullName;
        }
      }
      $chairs = implode(',', $chairs);
      $item = new DynamicItem();
      $item->set('type', $event->type);
      $item->set('title', strip_tags($event->title));
      $item->set('description', $event->description);
      $item->set('startDateTime', $startDT);
      $item->set('endDateTime', $endDT);
      $item->set('timezone', $event->timezone);
      $item->set('location', $event->location);
      $item->set('room', $event->room);
      $item->set('category', $event->category);
      $item->set('categoryId', $event->categoryId);
      $item->set('roomMapURL', $event->roomMapURL);
      $item->set('link', $event->url);
      $item->set('eventChair', $chairs);
      $item->set('iCal', $this->getIcalLink($event->url));
      $item->set('id', $event->id);
      $items[] = $item;
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getMappingSources() {
    return [
      'id' => [
        'label' => $this->t('Event ID'),
        'description' => $this->t('Unique ID of the event.'),
      ],
      'type' => [
        'label' => $this->t('Type'),
        'description' => $this->t('Type of the event'),
      ],
      'title' => [
        'label' => $this->t('Title'),
        'description' => $this->t('Title of the event'),
      ],
      'description' => [
        'label' => $this->t('Description'),
        'description' => $this->t('Description of the event'),
      ],
      'startDateTime' => [
        'label' => $this->t('Start date/time'),
        'description' => $this->t('Start date/time of the event'),
      ],
      'endDateTime' => [
        'label' => $this->t('End date/time'),
        'description' => $this->t('End date/time of the event'),
      ],
      'timezone' => [
        'label' => $this->t('Timezone'),
        'description' => $this->t('Timezone of the event'),
      ],
      'location' => [
        'label' => $this->t('Location'),
        'description' => $this->t('Location of the event'),
      ],
      'room' => [
        'label' => $this->t('Room'),
        'description' => $this->t('Room of the event'),
      ],
      'category' => [
        'label' => $this->t('Category'),
        'description' => $this->t('Indico category of the event'),
      ],
      'categoryId' => [
        'label' => $this->t('Category ID'),
        'description' => $this->t('Indico category ID of the event'),
      ],
      'roomMapURL' => [
        'label' => $this->t('Room Map URL'),
        'description' => $this->t('Link to a map showing the room'),
      ],
      'link' => [
        'label' => $this->t('Indico link'),
        'description' => $this->t('Indico link of the event'),
      ],
      'eventChair' => [
        'label' => $this->t('Event Chairs'),
        'description' => $this->t('Event Chairs from the event'),
      ],
      'iCal' => [
        'label' => $this->t('iCal link'),
        'description' => $this->t('Indico iCal link of the event'),
      ],
    ];
  }

  /**
   * Generate the url to Indico .ics file.
   */
  private function getIcalLink($url) {
    if (preg_match("/event\/([\d]+)\/?$/i", $url, $match)) {
      return INDICO_URL . '/export/event/' . $match[1] . '.ics';
    }
    return $url;
  }

}
