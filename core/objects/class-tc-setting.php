<?php
/**
 * Represents a forum setting.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCSetting extends TCObject
{
  /**
   * @since 0.01
   */
  public $setting_id;

  /**
   * @since 0.01
   */
  protected $setting_name;

  /**
   * @since 0.01
   */
  protected $type;

  /**
   * @since 0.01
   */
  protected $title;

  /**
   * @since 0.01
   */
  protected $value;

  /**
   * @since 0.01
   */
  protected $required;

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'setting_id';
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_settings';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'setting_name',
          'type',
          'title',
          'value',
          'required',
        ];
  }
}
