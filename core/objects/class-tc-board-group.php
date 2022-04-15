<?php
/**
 * Represents a group of forum boards.
 *
 * Referenced in TCBoard::$board_group_id
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCBoardGroup extends TCObject
{
  /**
   * @since 0.01
   */
  public $board_group_id;

  /**
   * @since 0.01
   */
  protected $board_group_name;

  /**
   * @since 0.01
   */
  protected $created_time;

  /**
   * @since 0.01
   */
  protected $updated_time;

  /**
   * @see TCObject::validate_field_value()
   * @since 0.01
   */
  public function validate_field_value($field_name, $value)
  {
    if (!parent::validate_field_value($field_name, $value)) {
      return false;
    }

    return true;
  }

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'board_group_id';
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_board_groups';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'board_group_name',
          'created_time',
          'updated_time',
        ];
  }
}
