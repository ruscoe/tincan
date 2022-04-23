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
   * @since 0.01
   */
  abstract public function open_connection();

  /**
   * @since 0.01
   */
  abstract public function close_connection();

  /**
   * @since 0.01
   */
  abstract public function query($query, $params = []);

  /**
   * @since 0.01
   */
  abstract public function get_last_insert_id();

  /**
   * @since 0.01
   */
  abstract public function get_last_error();
}
