<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

use \TCRole;

class TCUser extends TCObject {

  const ERR_USER = 'nouser';
  const ERR_PASSWORD = 'nopass';
  const ERR_ALREADY_EXISTS = 'exists';

  /**
   * @since 0.01
   */
  public $user_id;

  /**
   * @since 0.01
   */
  protected $username;

  /**
   * @since 0.01
   */
  protected $email;

  /**
   * @since 0.01
   */
  protected $password;

  /**
   * @since 0.02
   */
  protected $role_id;

  /**
   * @since 0.01
   */
  protected $created_time;

  /**
   * @since 0.01
   */
  protected $updated_time;

  /**
   * @since 0.02
   */
  protected TCRole $role;

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
      'password',
      'role_id',
      'created_time',
      'updated_time'
    );
  }

}
