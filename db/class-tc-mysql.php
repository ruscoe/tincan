<?php
/**
 * Interface for Tin Can database access objects (DAOs).
 *
 * @package Tin Can
 * @since 0.01
 */

 /**
  * @since 0.01
  */
class TCMySQL implements TCDB {

  /**
   * @since 0.01
   */
  $connection;

  /**
   * @since 0.01
   */
  $db_host;

  /**
   * @since 0.01
   */
  $db_user;

  /**
   * @since 0.01
   */
  $db_pass;

  /**
   * @since 0.01
   */
  $db_name;

  /**
   * @since 0.01
   */
  function __construct($db_host, $db_user, $db_pass, $db_name) {

    try {
      $this->connection = new mysqli('localhost', 'user', 'password', 'database');
    }
    catch (mysqli_sql_exception $e) {
      // TODO: Handle exception.
    }
  }

  /**
   * @since 0.01
   */
  protected function open_connection() {

  }

  /**
   * @since 0.01
   */
  protected function close_connection() {

  }

}
