<?php

namespace TinCan;

/**
 * Tin Can MySQL database service.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */

/**
 * @since 0.01
 */
class TCMySQL extends TCDB
{
    /**
     * @since 0.01
     */
    private $connection;

    /**
     * @see   TCDB::is_connected()
     * @since 0.06
     */
    public function is_connected()
    {
        try {
            // This is needed when using PHP 8 and above due to what looks like a bug.
            // @see https://github.com/joomlatools/joomlatools-framework/issues/554
            return ($this->connection instanceof MySQLi) && @$this->_connection->ping();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @see   TCDB::open_connection()
     * @since 0.01
     */
    public function open_connection()
    {
        if (!empty($this->connection) && !$this->is_connected()) {
            return $this->connection;
        }

        try {
            $this->connection = new \mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name, $this->db_port);
        } catch (\mysqli_sql_exception $e) {
            //echo $e->getMessage();
            throw new TCException('Database connection failed.');
        }

        if (!empty($this->connection->connect_errno)) {
            throw new TCException('Database connection failed: '.$this->connection->connect_errno);
        }

        return $this->connection;
    }

    /**
     * @see   TCDB::close_connection()
     * @since 0.01
     */
    public function close_connection()
    {
        if (!empty($this->connection) && $this->is_connected()) {
            $this->connection->close();
        }
    }

    /**
     * @see   TCDB::query()
     * @since 0.01
     */
    public function query($query, $params = [])
    {
        try {
            $this->open_connection();
        } catch (TCException $e) {
            throw new TCException($e->getMessage());
        }

        try {
            $prepared = $this->connection->prepare($query);
        } catch (\mysqli_sql_exception $e) {
            $this->close_connection();
            throw new TCException('Unable to prepare query for execution: '.$query);
        }

        if (!empty($params)) {
            $bind_param_type = '';
            foreach ($params as $param) {
                $bind_param_type .= (is_int($param)) ? 'i' : 's';
            }

            $prepared->bind_param($bind_param_type, ...$params);
        }

        // If query cannot be prepared, return null result.
        if (false === $prepared) {
            return null;
        }

        if (false !== $prepared->execute()) {
            $result = $prepared->get_result();

            $this->close_connection();

            if (!empty($result)) {
                $prepared->free_result();
                $prepared->close();

                return $result;
            }
        } elseif (!empty($prepared->error)) {
            throw new TCException('Unable to execute query: '.$prepared->error);
        }

        return null;
    }

    /**
     * @see   TCDB::get_last_insert_id()
     * @since 0.01
     */
    public function get_last_insert_id()
    {
        return $this->connection->insert_id;
    }

    /**
     * @see   TCDB::get_last_error()
     * @since 0.01
     */
    public function get_last_error()
    {
        return $this->connection->error;
    }
}
