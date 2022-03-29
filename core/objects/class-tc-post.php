<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCPost extends TCObject {

  /**
   * @since 0.01
   */
  public $post_id;

  /**
   * @since 0.01
   */
  public $user_id;

  /**
   * @since 0.01
   */
  public $thread_id;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'post_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_posts';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'post_id',
      'user_id',
      'thread_id'
    );
  }

}
