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
   * @since 0.01
   */
  public $password;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_password_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function verify_password_hash($password, $hash) {
    return password_verify($password, $hash);
  }

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
      'username',
      'email',
      'password'
    );
  }

}
