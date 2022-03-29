<?php
/**
 * Tin Can database layer.
 *
 * @package Tin Can
 * @since 0.01
 */

 /**
  * @since 0.01
  */
class TCData {

  /**
   * @since 0.01
   */
  private $database;

  /**
   * @since 0.01
   */
  function __construct() {
    $db_class = TC_DB_CLASS;
    $db_host = TC_DB_HOST;
    $db_user = TC_DB_USER;
    $db_pass = TC_DB_PASS;
    $db_name = TC_DB_NAME;

    $this->database = new $db_class($db_host, $db_user, $db_pass, $db_name);
  }

  /**
   * @since 0.01
   */
  function load_object($class, $id) {
    $db_table = $class->get_db_table();
    $primary_key = $class->get_primary_key();

    $query = "SELECT * FROM `{$db_table}` WHERE `{$primary_key}` = {$id}";

    $this->database->open_connection();

    $result = $this->database->query($query);

    $row = $result->fetch_object();

    //var_dump($row);

    $this->database->close_connection();

    if (!empty($row)) {
      return new $class($row);
    }

    return NULL;
  }

  /**
   * @since 0.01
   */
  function load_objects($class, $ids = array(), $conditions = array()) {
    $db_table = $class->get_db_table();

    // TODO: Use optional object IDs.
    $query = "SELECT * FROM `{$db_table}`";

    if (!empty($conditions)) {
      $query .= " WHERE";
      foreach ($conditions as $condition) {
        // TODO: Allow conditions other than equals.
        $query .= " `{$condition['field']}` = '{$condition['value']}'";
      }
    }

    $this->database->open_connection();

    $result = $this->database->query($query);

    $objects = array();
    while($object = $result->fetch_object()) {
      $objects[] = new $class($object);
    }

    $this->database->close_connection();

    return $objects;
  }

}
