<?php

namespace TinCan\db;

/**
 * Base class for Tin Can database services.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
abstract class TCDB
{
    /**
     * @since 0.01
     */
    protected $db_host;

    /**
     * @since 0.01
     */
    protected $db_user;

    /**
     * @since 0.01
     */
    protected $db_pass;

    /**
     * @since 0.01
     */
    protected $db_name;

    /**
     * @since 0.14
     */
    protected $db_port;

    public function __construct($db_host, $db_user, $db_pass, $db_name, $db_port)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        $this->db_port = $db_port;
    }

    /**
     * Tests for an existing database connection.
     *
     * @since 0.06
     *
     * @return bool true if a database connection exists
     */
    abstract public function is_connected();

    /**
     * Opens a database connection.
     *
     * @since 0.01
     *
     * @return object the database connection object
     */
    abstract public function open_connection();

    /**
     * Closes an existing database connection.
     *
     * @since 0.01
     */
    abstract public function close_connection();

    /**
     * Performs a database query.
     *
     * @since 0.01
     *
     * @param string $query  the query string
     * @param array  $params optional array of query parameters
     *
     * @return object the query result object
     */
    abstract public function query($query, $params = []);

    /**
     * Gets the unique ID of the last created record.
     *
     * @since 0.01
     *
     * return int the last created ID
     */
    abstract public function get_last_insert_id();

    /**
     * Gets the last error message from the database server.
     *
     * @since 0.01
     *
     * @return string the error message
     */
    abstract public function get_last_error();
}
