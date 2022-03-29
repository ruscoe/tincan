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

    $this->database->close_connection();

    if (!empty($row)) {
      return new $class($row);
    }

    return NULL;
  }

  /**
   * @since 0.01
   */
  function save_object(TCObject $object) {
    $db_table = $object->get_db_table();
    $primary_key = $object->get_primary_key();
    $db_fields = $object->get_db_fields();

    $this->database->open_connection();

    if (empty($object->$primary_key)) {
      // New object to create.
      $sql_field_names = '';
      $sql_field_values = '';

      foreach ($db_fields as $field) {
        $sql_field_names .= "`{$field}`,";
        $sql_field_values .= "'{$object->$field}',";
      }

      $sql_field_names = substr($sql_field_names, 0, -1);
      $sql_field_values = substr($sql_field_values, 0, -1);

      $query = "INSERT INTO `{$db_table}` ({$sql_field_names}) VALUES ({$sql_field_values})";

      $result = $this->database->query($query);
      $insert_id = $this->database->get_last_insert_id();

      $object->$primary_key = $insert_id;
    }
    else {
      // TODO: Existing object to update.
      //$query = "UPDATE `{$db_table}` WHERE `{$primary_key}` = '{$object->$primary_key}'";
    }

    $this->database->close_connection();

    return $object;
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