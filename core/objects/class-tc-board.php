<?php
/**
 * Represents a forum board.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCBoard extends TCObject
{
  /**
   * @since 0.01
   */
  public $board_id;

  /**
   * @since 0.01
   */
  protected $board_name;

  /**
   * Reference to TCBoardGroup::$board_group_id.
   *
   * @since 0.01
   */
  protected $board_group_id;

  /**
   * @since 0.02
   */
  protected $description;

  /**
   * @since 0.01
   */
  protected $created_time;

  /**
   * @since 0.01
   */
  protected $updated_time;

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'board_id';
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_boards';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'board_name',
          'board_group_id',
          'description',
          'created_time',
          'updated_time',
        ];
  }
}
