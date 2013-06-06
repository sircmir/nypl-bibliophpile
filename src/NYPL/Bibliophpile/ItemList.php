<?php

/**
 * @file
 * List class 
 */

namespace NYPL\Bibliophpile;

class ItemList extends DataResource {

  protected $items;

  /**
   * List object constructor.
   *
   * @param StdObj $data 
   *   Parsed JSON for the object.
   * @param Client $client
   *   Client for future connections
   */
  public function __construct($data, $client) {
    parent::__construct($data);
    $this->data->created
      = new \DateTime($this->data->created, new \DateTimeZone('utc'));
    $this->data->updated
      = new \DateTime($this->data->updated, new \DateTimeZone('utc'));
    $this->data->user = new User($this->data->user, $client);

    $this->initOptionalProperties();

    if ($this->data->list_type !== NULL) {
      $this->data->list_type = new ListType($this->data->list_type);
    }

    $this->initItems($client);

  }

  /**
   * Initialize optional properties.
   */
  protected function initOptionalProperties() {
    $this->initOptionalProperty('list_type', 'object');
  }

  /**
   * Initialize the list of items.
   */
  protected function initItems($client) {
    $this->items = array();
    foreach ($this->data->list_items as $item) {
      if ($item->list_item_type === 'title') {
        $this->items[] = new ListItemTitle($item, $client);
      }
      else {
        $this->items[] = new ListItemUrl($item, $client);
      }
    }
  }

  /**
   * Returns the list's name.
   *
   * @return string
   *   The name
   */
  public function name() {
    return $this->data->name;
  }

  /**
   * Returns the list's id.
   *
   * @return string
   *   The id
   */
  public function id() {
    return $this->data->id;
  }

  /**
   * Returns the count of items in the list.
   *
   * @return int
   *   The item count
   */
  public function itemCount() {
    return $this->data->item_count;
  }

  /**
   * Returns the date the list was created.
   *
   * @return DateTime
   *   The date
   */
  public function created() {
    return $this->data->created;
  }

  /**
   * Returns the date the list was last updated.
   *
   * @return DateTime
   *   The date
   */
  public function updated() {
    return $this->data->updated;
  }

  /**
   * Returns the list type.
   *
   * @return \NYPL\Bibliophpile\ListType
   *   The list type
   */
  public function listType() {
    return $this->data->list_type;
  }

  /**
   * Returns the user who created this list.
   *
   * @return \NYPL\Bibliophpile\User
   *   The user
   */
  public function user() {
    return $this->data->user;
  }

  /**
   * Returns the URL for the list on BiblioCommons.
   *
   * @return string
   *   The URL
   */
  public function details() {
    return $this->data->details_url;
  }

  /**
   * Returns the items in the list.
   *
   * @return array
   *   An Array of ListItemTitle and ListItemUrl objects
   */
  public function items() {
    return $this->items;
  }
}