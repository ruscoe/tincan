<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCUser extends TCObject {

  /**
   * @since 0.01
   */
  public $user_id;

  /**
   * @since 0.01
   */
  public $username;

  /**
   * @since 0.01
   */
  public $email;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'user_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_users';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'user_id',
      'username',
      'email'
    );
  }

}
