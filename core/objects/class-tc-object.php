<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

abstract class TCObject {

  /**
   * @since 0.01
   */
  function __construct($object = NULL) {
    if (!empty($object)) {
      $primary_key = $this->get_primary_key();
      $this->$primary_key = (!empty($object->$primary_key)) ? $object->$primary_key : NULL;

      $this->populate_from_db($object);
    }
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function load() {

  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function save() {

  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function populate_from_db($object) {
    $db_fields = $this->get_db_fields();

    foreach ($db_fields as $field) {
      if (isset($object->$field)) {
        $this->$field = $object->$field;
      }
    }
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  abstract public function get_primary_key();

  /**
   * TODO
   *
   * @since 0.01
   */
  abstract public function get_db_table();

  /**
   * TODO
   *
   * @since 0.01
   */
  abstract public function get_db_fields();

}
