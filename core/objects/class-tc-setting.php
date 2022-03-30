<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCSetting extends TCObject {

  /**
   * @since 0.01
   */
  public $setting_id;

  /**
   * @since 0.01
   */
  public $setting_name;

  /**
   * @since 0.01
   */
  public $value;

  /**
   * @since 0.01
   */
  public $required;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'setting_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_settings';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'setting_name',
      'value',
      'required'
    );
  }

}
