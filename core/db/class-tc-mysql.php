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
   * @since 0.01
   */
  public function open_connection()
  {
    try {
      $this->connection = new \mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    } catch (mysqli_sql_exception $e) {
      // TODO: Handle exception.
      exit($e->message);
    }

    return $this->connection;
  }

  /**
   * @since 0.01
   */
  public function close_connection()
  {
  }

  /**
   * @since 0.01
   */
  public function query($query, $params = [])
  {
    $prepared = $this->connection->prepare($query);

    foreach ($params as $param) {
      if (is_int($param)) {
        $prepared->bind_param('i', $param);
      }
      else {
        $prepared->bind_param('s', $param);
      }
    }

    $prepared->execute();

    $result = $prepared->get_result();

    $prepared->free_result();
    $prepared->close();

    return $result;
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
