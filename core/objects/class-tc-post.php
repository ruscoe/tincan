<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCPost extends TCObject {

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
      'post_id'
    );
  }

}
