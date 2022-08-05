<?php

namespace TinCan;

/**
 * Tin Can MySQL database service.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
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
   * Tests for an existing database connection.
   *
   * This is needed when using PHP 8 and above due to what looks like a bug.
   *
   * @see https://github.com/joomlatools/joomlatools-framework/issues/554
   * @since 0.06
   */
  public function is_connected()
  {
    try {
      return ($this->connection instanceof MySQLi) && @$this->_connection->ping();
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * @since 0.01
   */
  public function open_connection()
  {
    if (!empty($this->connection) && !$this->is_connected()) {
      return $this->connection;
    }

    try {
      $this->connection = new \mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    } catch (mysqli_sql_exception $e) {
      throw new TCException('Database connection failed.');
    }

    if (!empty($this->connection->connect_errno)) {
      throw new TCException('Database connection failed: '.$this->connection->connect_errno);
    }

    return $this->connection;
  }

  /**
   * @since 0.01
   */
  public function close_connection()
  {
    if (!empty($this->connection) && $this->is_connected()) {
      $this->connection->close();
    }
  }

  /**
   * @since 0.01
   */
  public function query($query, $params = [])
  {
    try {
      $this->open_connection();
    } catch (TCException $e) {
      throw new TCException($e->getMessage());
    }

    $prepared = $this->connection->prepare($query);

    if (empty($prepared)) {
      $this->close_connection();
      throw new TCException('Unable to prepare query for execution: '.$query);
    }

    foreach ($params as $param) {
      if (is_int($param)) {
        $prepared->bind_param('i', $param);
      } else {
        $prepared->bind_param('s', $param);
      }
    }

    if (false !== $prepared->execute()) {
      $result = $prepared->get_result();

      $this->close_connection();

      if (!empty($result)) {
        $prepared->free_result();
        $prepared->close();

        return $result;
      } else {
        // If $result is empty but the query executed, then it was a query
        // that does not return a result (i.e. DELETE).
        // Check for affected rows.
        return ($prepared->affected_rows > 0);
      }
    } else if (!empty($prepared->error)) {
      throw new TCException('Unable to execute query: '.$prepared->error);
    }

    return null;
  }

  /**
   * @since 0.01
   */
  public function get_last_insert_id()
  {
    return $this->connection->insert_id;
  }

  /**
   * @since 0.01
   */
  public function get_last_error()
  {
    return $this->connection->error;
  }
}
