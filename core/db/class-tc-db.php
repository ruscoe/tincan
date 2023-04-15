<?php

namespace TinCan;

/**
 * Base class for Tin Can database services.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

/**
 * @since 0.01
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

    public function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }

    /**
     * Tests for an existing database connection.
     *
     * @since 0.06
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
     * @param string $query the query string
     * @param array $params optional array of query parameters
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
