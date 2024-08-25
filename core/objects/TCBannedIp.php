<?php

namespace TinCan\objects;

/**
 * Represents a banned IP address.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   1.0.0
 */
class TCBannedIp extends TCObject
{
    /**
     * @since 1.0.0
     */
    public $banned_ip_id;

    /**
     * @since 1.0.0
     */
    protected $ip;

    /**
     * @see   TCObject::get_primary_key()
     * @since 1.0.0
     */
    public function get_primary_key()
    {
        return 'banned_ip_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 1.0.0
     */
    public function get_primary_key_value()
    {
        return $this->banned_ip_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 1.0.0
     */
    public function get_db_table()
    {
        return 'tc_banned_ips';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 1.0.0
     */
    public function get_db_fields()
    {
        return [
              'ip',
            ];
    }
}
