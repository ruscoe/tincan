<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

use TCRole;

class TCUser extends TCObject
{
    public const ACT_CREATE_POST = 'create-post';
    public const ACT_CREATE_THREAD = 'create-thread';
    public const ACT_ACCESS_ADMIN = 'access-admin';

    public const ERR_USER = 'nouser';
    public const ERR_PASSWORD = 'nopass';
    public const ERR_ALREADY_EXISTS = 'exists';
    public const ERR_NOT_AUTHORIZED = 'auth';

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
     * @since 0.02
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

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_password_hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function verify_password_hash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'user_id';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_users';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_fields()
    {
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
