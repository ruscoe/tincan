<?php

namespace TinCan\objects;

/**
 * Represents a forum user.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCUser extends TCObject
{
    public const ACT_LOG_IN = 'log-in';
    public const ACT_CREATE_POST = 'create-post';
    public const ACT_CREATE_THREAD = 'create-thread';
    public const ACT_EDIT_ANY_POST = 'edit-any-post';
    public const ACT_EDIT_ANY_THREAD = 'edit-any-thread';
    public const ACT_DELETE_ANY_POST = 'delete-any-post';
    public const ACT_DELETE_ANY_THREAD = 'delete-any-thread';
    public const ACT_EDIT_ANY_USER = 'edit-any-user';
    public const ACT_REPORT_ANY_POST = 'report-any-post';
    public const ACT_ACCESS_ADMIN = 'access-admin';

    public const ERR_USER = 'user';
    public const ERR_EMAIL = 'email';
    public const ERR_PASSWORD = 'pass';
    public const ERR_USERNAME_EXISTS = 'username-exists';
    public const ERR_EMAIL_EXISTS = 'email-exists';
    public const ERR_NOT_AUTHORIZED = 'auth';

    public const MIN_PASSWORD_LENGTH = 8;
    public const MIN_USERNAME_LENGTH = 3;
    public const MAX_USERNAME_LENGTH = 16;

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
     * Populated only when the user has requested a password reset.
     *
     * @since 0.07
     */
    protected $password_reset_code;

    /**
     * Reference to TCRole::$role_id.
     *
     * @since 0.02
     */
    protected $role_id;

    /**
     * @since 0.05
     */
    protected $avatar;

    /**
     * @since 0.12
     */
    protected $suspended;

    /**
     * @since 1.0.0
     */
    protected $signup_ip;

    /**
     * @since 1.0.0
     */
    protected $last_ip;

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
        // All users can log in except suspended.
        if ((self::ACT_LOG_IN == $action) && !$this->suspended) {
            return true;
        }

        // Suspended users can't do anything.
        if ($this->suspended) {
            return false;
        }

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
     * Determines whether this user can edit a user.
     *
     * @since 0.05
     *
     * @param TCUser $user the user to check
     *
     * @return bool true if the user may edit the user
     */
    public function can_edit_user(TCUser $user)
    {
        // Check for roles that can edit any post.
        if ($this->can_perform_action(self::ACT_EDIT_ANY_USER)) {
            return true;
        }

        // User can edit themselves.
        if ($user->user_id == $this->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determines whether this user can edit a thread.
     *
     * @since 0.05
     *
     * @param TCThread $thread the thread to check
     *
     * @return bool true if the user may edit the thread
     */
    public function can_edit_thread(TCThread $thread)
    {
        // Check for roles that can edit any thread.
        if ($this->can_perform_action(self::ACT_EDIT_ANY_THREAD)) {
            return true;
        }

        // User can edit their own threads.
        if ($thread->created_by_user == $this->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determines whether this user can delete a thread.
     *
     * @since 0.05
     *
     * @param TCThread $thread the thread to check
     *
     * @return bool true if the user may delete the thread
     */
    public function can_delete_thread(TCThread $thread)
    {
        // Check for roles that can delete any thread.
        if ($this->can_perform_action(self::ACT_DELETE_ANY_THREAD)) {
            return true;
        }

        // User can delete their own threads.
        if ($thread->created_by_user == $this->user_id) {
            return true;
        }

        return false;
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
     * Determines whether this user can report a post.
     *
     * @since 0.16
     *
     * @param TCPost $post the post to check
     *
     * @return bool true if the user may report the post
     */
    public function can_report_post(TCPost $post)
    {
        // Check for roles that can report any post.
        if ($this->can_perform_action(self::ACT_REPORT_ANY_POST)) {
            return true;
        }

        return false;
    }

    /**
     * Generates a random password.
     *
     * @since 0.06
     *
     * @param int $length length of password to generate
     *
     * @return string the password in plain text
     */
    public function generate_password($length = 0)
    {
        if ($length < self::MIN_PASSWORD_LENGTH) {
            $length = self::MIN_PASSWORD_LENGTH;
        }

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        $password = '';

        $max_char = (strlen($chars) - 1);

        for ($i = 0; $i < $length; ++$i) {
            $index = rand(0, $max_char);
            $password .= substr($chars, $index, 1);
        }

        return $password;
    }

    /**
     * Generates a random code that allows the user to reset their password.
     *
     * @since 0.07
     *
     * @param int $length length of the code to generate
     *
     * @return string the password reset code
     */
    public function generate_password_reset_code($length = 16)
    {
        // Use the password generator to create a random string.
        $code = $this->generate_password($length);
        // Hash the random string to create the code.
        $hash = md5($code);

        return $hash;
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
        return password_hash($password, PASSWORD_BCRYPT);
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
     * Determines if a username can be used.
     *
     * @param string $username the username to test
     *
     * @return bool true if username is valid
     *
     * @since 0.04
     */
    public function validate_username($username)
    {
        // Check username length.
        if (strlen($username) < self::MIN_USERNAME_LENGTH) {
            return false;
        }

        if (strlen($username) > self::MAX_USERNAME_LENGTH) {
            return false;
        }

        // Check for non-alphanumeric characters.
        if (!ctype_alnum($username)) {
            return false;
        }

        return true;
    }

    /**
     * Determines if an email address can be used.
     *
     * @since 0.04
     *
     * @param string $email the password to test
     *
     * @return bool true if email is valid
     */
    public function validate_email($email)
    {
        // TODO: Validate email format.
        if (empty($email)) {
            return false;
        }

        return true;
    }

    /**
     * Determines if a password can be used.
     *
     * @since 0.04
     *
     * @param string $password the password to test
     *
     * @return bool true if password is valid
     */
    public function validate_password($password)
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return false;
        }

        return true;
    }

    /**
     * @see   TCObject::get_name()
     * @since 0.06
     */
    public function get_name()
    {
        return $this->username;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'user_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.04
     */
    public function get_primary_key_value()
    {
        return $this->user_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_users';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.01
     */
    public function get_db_fields()
    {
        return [
              'username',
              'email',
              'password',
              'password_reset_code',
              'role_id',
              'avatar',
              'suspended',
              'signup_ip',
              'last_ip',
              'created_time',
              'updated_time',
            ];
    }
}
