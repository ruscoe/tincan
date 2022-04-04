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
  function __construct($object = null) {
    if (!empty($object)) {
      $primary_key = $this->get_primary_key();
      $this->$primary_key = (!empty($object->$primary_key)) ? $object->$primary_key : null;

      $this->populate_from_db($object);
    }
  }

  /**
   * @since 0.01
   */
  public function __set($name, $value) {
    if ($this->validate_field_value($name, $value)) {
      $this->$name = $value;
    }
  }

  /**
   * TODO: Remove.
   *
   * @since 0.01
   */
  public function load() {

  }

  /**
   * TODO: Remove.
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
      if ($this->validate_field_value($field, $object->$field)) {
        $this->$field = $object->$field;
      }
    }
  }

  /**
   * TODO: Override for object-specific validation.
   *
   * @since 0.01
   */
  public function validate_field_value($field_name, $value) {
    return (!empty($value));
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
