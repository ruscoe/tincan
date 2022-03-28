<?php
/**
 * Base class for Tin Can database services.
 *
 * @package Tin Can
 * @since 0.01
 */

 /**
  * @since 0.01
  */
abstract class TCDB {

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

  function __construct($db_host, $db_user, $db_pass, $db_name) {
    $this->db_host = $db_host;
    $this->db_user = $db_user;
    $this->db_pass = $db_pass;
    $this->db_name = $db_name;
  }

  /**
   * @since 0.01
   */
  abstract function open_connection();

  /**
   * @since 0.01
   */
  abstract function close_connection();

  /**
   * @since 0.01
   */
  abstract function query($query);

}
