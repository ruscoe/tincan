<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCThread extends TCObject {

  /**
   * @since 0.01
   */
  public $thread_id;

  /**
   * @since 0.01
   */
  public $board_id;

  /**
   * @since 0.01
   */
  public $thread_title;

  /**
   * @since 0.01
   */
  public $last_post_time;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'thread_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_threads';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'board_id',
      'thread_title',
      'last_post_time'
    );
  }

}
