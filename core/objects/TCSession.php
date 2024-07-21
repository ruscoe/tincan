<?php

namespace TinCan\objects;

/**
 * Represents a forum user's session.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCSession extends TCObject
{
    /**
     * @since 0.16
     */
    public $session_id;

    /**
     * @since 0.16
     */
    protected $user_id;

    /**
     * @since 0.16
     */
    protected $hash;

    /**
     * @since 0.16
     */
    protected $created_time;

    /**
     * @since 0.16
     */
    protected $expiration_time;

    /**
     * Generates a random hash value for the user's session.
     *
     * @since 0.16
     */
    public function generate_random_hash()
    {
        $random = bin2hex(random_bytes(32));
        $hash = md5($random);

        return $hash;
    }

    /**
     * Gets the session hash.
     *
     * @since 0.16
     */
    public function get_hash()
    {
        return $this->hash;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.16
     */
    public function get_primary_key()
    {
        return 'session_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.16
     */
    public function get_primary_key_value()
    {
        return $this->session_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.16
     */
    public function get_db_table()
    {
        return 'tc_sessions';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.16
     */
    public function get_db_fields()
    {
        return [
              'user_id',
              'hash',
              'created_time',
              'expiration_time',
            ];
    }
}
