<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCBoardGroup extends TCObject {

  /**
   * @since 0.01
   */
  public $board_group_id;

  /**
   * @since 0.01
   */
  public $board_group_name;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_board_groups';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'board_group_id',
      'board_group_name'
    );
  }

}
