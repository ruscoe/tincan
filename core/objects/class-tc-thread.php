<?php

namespace TinCan;

/**
 * Represents a forum thread.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCThread extends TCObject
{
  /**
   * @since 0.01
   */
  public $thread_id;

  /**
   * Reference to TCBoard::$board_id.
   *
   * @since 0.01
   */
  protected $board_id;

  /**
   * @since 0.01
   */
  protected $thread_title;

  /**
   * Reference to TCUser::$user_id.
   *
   * @since 0.02
   */
  protected $created_by_user;

  /**
   * Reference to TCUser::$user_id.
   *
   * @since 0.02
   */
  protected $updated_by_user;

  /**
   * @since 0.01
   */
  protected $created_time;

  /**
   * @since 0.01
   */
  protected $updated_time;

  /**
   * @see TCObject::get_parent()
   * @since 0.04
   */
  public function get_parent() {
    $parent = null;

    if (!empty($this->board_id)) {
      $parent = new TCBoard();
      $parent->board_id = $this->board_id;
    }

    return $parent;
  }

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'thread_id';
  }

  /**
   * @see TCObject::get_primary_key_value()
   * @since 0.04
   */
  public function get_primary_key_value()
  {
    return $this->thread_id;
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_threads';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'board_id',
          'thread_title',
          'created_by_user',
          'updated_by_user',
          'created_time',
          'updated_time',
        ];
  }
}
