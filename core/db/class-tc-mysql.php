<?php
/**
 * Tin Can MySQL database service.
 *
 * @package Tin Can
 * @since 0.01
 */

 /**
  * @since 0.01
  */
class TCMySQL extends TCDB {

  /**
   * @since 0.01
   */
  private $connection;

  /**
   * @since 0.01
   */
  function open_connection() {
    try {
      $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    }
    catch (mysqli_sql_exception $e) {
      // TODO: Handle exception.
      die($e->message);
    }

    return $this->connection;
  }

  /**
   * @since 0.01
   */
  function close_connection() {

  }

  /**
   * @since 0.01
   */
  function query($query) {
    return $this->connection->query($query);
  }

}
