<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCThread extends TCObject {

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
      'thread_id'
    );
  }

}
