<?php

namespace TinCan\objects;

/**
 * Represents a pending forum user that needs confirmation.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.07
 */
class TCPendingUser extends TCObject
{
    /**
     * @since 0.07
     */
    public $pending_user_id;

    /**
     * Reference to TCUser::$user_id.
     *
     * @since 0.07
     */
    public $user_id;

    /**
     * @since 0.07
     */
    protected $confirmation_code;

    /**
     * Generates a random code that allows the user to confirm their account.
     *
     * @since 0.07
     *
     * @return string the confirmation code
     */
    public function generate_confirmation_code()
    {
        $length = 16;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        $code = '';

        $max_char = (strlen($chars) - 1);

        for ($i = 0; $i < $length; ++$i) {
            $index = rand(0, $max_char);
            $code .= substr($chars, $index, 1);
        }

        // Hash the random string to create the code.
        $hash = md5($code);

        return $hash;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.07
     */
    public function get_primary_key()
    {
        return 'pending_user_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.07
     */
    public function get_primary_key_value()
    {
        return $this->pending_user_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.07
     */
    public function get_db_table()
    {
        return 'tc_pending_users';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.07
     */
    public function get_db_fields()
    {
        return [
              'user_id',
              'confirmation_code',
            ];
    }

    /**
     * @see   TCObject::get_db_relationships()
     */
    public function get_db_relationships()
    {
        return [];
    }
}
