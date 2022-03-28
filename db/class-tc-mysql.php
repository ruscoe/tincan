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
  private $connection;

  /**
   * @since 0.01
   */
  private $db_host;

  /**
   * @since 0.01
   */
  private $db_user;

  /**
   * @since 0.01
   */
  private $db_pass;

  /**
   * @since 0.01
   */
  private $db_name;

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
  function open_connection() {

  }

  /**
   * @since 0.01
   */
  function close_connection() {

  }

}
