<?php

namespace TinCan;

/**
 * Represents a forum user.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

use TinCan\TCPost;
use TinCan\TCRole;
use TinCan\TCThread;

class TCUser extends TCObject
{
  public const ACT_CREATE_POST = 'create-post';
  public const ACT_CREATE_THREAD = 'create-thread';
  public const ACT_EDIT_ANY_POST = 'edit-any-post';
  public const ACT_EDIT_ANY_THREAD = 'edit-any-thread';
  public const ACT_DELETE_ANY_POST = 'delete-any-post';
  public const ACT_DELETE_ANY_THREAD = 'delete-any-thread';
  public const ACT_ACCESS_ADMIN = 'access-admin';

  public const ERR_USER = 'user';
  public const ERR_EMAIL = 'email';
  public const ERR_PASSWORD = 'pass';
  public const ERR_USERNAME_EXISTS = 'username-exists';
  public const ERR_EMAIL_EXISTS = 'email-exists';
  public const ERR_NOT_AUTHORIZED = 'auth';

  public const MIN_PASSWORD_LENGTH = 8;
  public const MIN_USERNAME_LENGTH = 3;

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
   * Reference to TCRole::$role_id.
   *
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
   * Determines whether this user can perform an action based on their role.
   *
   * @since 0.02
   *
   * @param string $action one of the ACT_* constants in this class
   *
   * @return bool true if the user may perform the action
   */
  public function can_perform_action($action)
  {
    if (!empty($this->role)) {
      $allowed_actions = explode(',', $this->role->allowed_actions);

      foreach ($allowed_actions as $allowed_action) {
        if ($action == $allowed_action) {
          return true;
        }
      }
    }

    return false;
  }

  // TODO
  public function can_edit_thread(TCThread $thread)
  {
  }

  // TODO
  public function can_delete_thread(TCThread $thread)
  {
  }

  /**
   * Determines whether this user can edit a post.
   *
   * @since 0.04
   *
   * @param TCPost $post the post to check
   *
   * @return bool true if the user may edit the post
   */
  public function can_edit_post(TCPost $post)
  {
    // Check for roles that can edit any post.
    if ($this->can_perform_action(self::ACT_EDIT_ANY_POST)) {
      return true;
    }

    // User can edit their own posts.
    if ($post->user_id == $this->user_id) {
      return true;
    }

    return false;
  }

  /**
   * Determines whether this user can delete a post.
   *
   * @since 0.04
   *
   * @param TCPost $post the post to check
   *
   * @return bool true if the user may delete the post
   */
  public function can_delete_post(TCPost $post)
  {
    // Check for roles that can edit any post.
    if ($this->can_perform_action(self::ACT_DELETE_ANY_POST)) {
      return true;
    }

    // User can delete their own posts.
    if ($post->user_id == $this->user_id) {
      return true;
    }

    return false;
  }

  /**
   * Converts a password to a hash for security.
   *
   * @since 0.01
   *
   * @param string $password to password to hash
   *
   * @return string the password hash
   */
  public function get_password_hash($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * Validates a password against a password hash.
   *
   * @since 0.01
   *
   * @param string $password the password to test
   * @param string $hash     the password hash to test against
   *
   * @return bool true if the password and hash match
   */
  public function verify_password_hash($password, $hash)
  {
    return password_verify($password, $hash);
  }

  /**
   * TODO:.
   *
   * @since 0.04
   */
  public function validate_username($username)
  {
    if (strlen($username) < self::MIN_USERNAME_LENGTH) {
      return false;
    }

    return true;
  }

  /**
   * TODO:.
   *
   * @since 0.04
   */
  public function validate_email($email)
  {
    if (empty($email)) {
      return false;
    }

    return true;
  }

  /**
   * TODO:.
   *
   * @since 0.04
   */
  public function validate_password($password)
  {
    if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
      return false;
    }

    return true;
  }

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'user_id';
  }

  /**
   * @see TCObject::get_primary_key_value()
   * @since 0.04
   */
  public function get_primary_key_value()
  {
    return $this->user_id;
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_users';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'username',
          'email',
          'password',
          'role_id',
          'created_time',
          'updated_time',
        ];
  }
}
