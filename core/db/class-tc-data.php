<?php

namespace TinCan;

/**
 * Tin Can database layer.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCData
{
  /**
   * @since 0.01
   */
  private $database;

  /**
   * @since 0.01
   */
  public function __construct()
  {
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
  public function run_query($query)
  {
    try {
      $result = $this->database->query($query);
    } catch (TCException $e) {
      throw new TCException('Unable to run DB query.');
    }

    return $result;
  }

  /**
   * @since 0.01
   *
   * @return array associative array of forum settings
   *
   * @throws TCException
   */
  public function load_settings()
  {
    $setting = new TCSetting();
    $db_table = $setting->get_db_table();

    $settings = [];

    $query = "SELECT `setting_name`, `value` FROM `{$db_table}`";

    try {
      $result = $this->database->query($query);
    } catch (TCException $e) {
      throw new TCException('Unable to load forum settings.');
    }

    while ($object = $result->fetch_object()) {
      $settings[$object->setting_name] = $object->value;
    }

    return $settings;
  }

  /**
   * @since 0.02
   */
  public function load_user($user_id)
  {
    try {
      $user = $this->load_object(new TCUser(), $user_id);
    } catch (TCException $e) {
      throw new TCException($e->getMessage());
    }

    if (!empty($user)) {
      try {
        $user->role = $this->load_object(new TCRole(), $user->role_id);
      } catch (TCException $e) {
        throw new TCException($e->getMessage());
      }

      return $user;
    }

    return null;
  }

  /**
   * @since 0.01
   */
  public function load_object($class, $id)
  {
    if (empty($class) || empty($id)) {
      return null;
    }

    $db_table = $class->get_db_table();
    $primary_key = $class->get_primary_key();

    $query = "SELECT * FROM `{$db_table}` WHERE `{$primary_key}` = ?";

    try {
      $result = $this->database->query($query, [$id]);
    } catch (TCException $e) {
      throw new TCException($e->getMessage());
    }

    if (empty($result)) {
      return null;
    }

    $row = $result->fetch_object();

    if (!empty($row)) {
      return new $class($row);
    }

    return null;
  }

  /**
   * @since 0.01
   */
  public function save_object(TCObject $object)
  {
    $db_table = $object->get_db_table();
    $primary_key = $object->get_primary_key();
    $db_fields = $object->get_db_fields();

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

      // if (!$result) {
      //   throw new TCException('DB query failed: '.$query);
      // }

      $insert_id = $this->database->get_last_insert_id();

      $object->$primary_key = $insert_id;
    } else {
      // Existing object to update.
      $sql_fields = [];

      foreach ($db_fields as $field) {
        $sql_fields[] = "`{$field}` = '{$object->$field}'";
      }

      $query = "UPDATE `{$db_table}` SET";
      $query .= implode(',', $sql_fields);
      $query .= " WHERE `{$primary_key}` = '{$object->$primary_key}'";

      $result = $this->database->query($query);

      // if (!$result) {
      //   throw new TCException('DB query failed: '.$query);
      // }
    }

    return $object;
  }

  /**
   * @since 0.01
   */
  public function load_objects($class, $ids = [], $conditions = [], $order = [], $offset = 0, $limit = 0)
  {
    $db_table = $class->get_db_table();
    $primary_key = $class->get_primary_key();

    $query = "SELECT * FROM `{$db_table}`";

    if (!empty($ids)) {
      $ids_in = implode(',', $ids);
      $query .= " WHERE `{$primary_key}` IN ({$ids_in})";
    } elseif (!empty($conditions)) {
      $query .= ' WHERE';
      foreach ($conditions as $condition) {
        if (!$this->validate_object_field($class, $condition['field'])) {
          throw new TCException('Invalid field '.$db_table.'.'.$condition['field']);
        }
        // TODO: Allow conditions other than equals.
        $query .= " `{$condition['field']}` = '{$condition['value']}'";
      }
    }

    if (!empty($order)) {
      $query .= " ORDER BY {$order['field']} {$order['direction']}";
    }

    if ($limit > 0) {
      $query .= " LIMIT {$offset}, {$limit}";
    }

    $result = $this->database->query($query);

    $objects = [];

    if ($result) {
      while ($object = $result->fetch_object()) {
        $objects[] = new $class($object);
      }
    }

    return $objects;
  }

  /**
   * @since 0.02
   */
  public function count_objects($class, $conditions = [])
  {
    $db_table = $class->get_db_table();
    $primary_key = $class->get_primary_key();

    $query = "SELECT COUNT(*) `count` FROM `{$db_table}`";

    if (!empty($conditions)) {
      $query .= ' WHERE';
      foreach ($conditions as $condition) {
        // TODO: Allow conditions other than equals.
        $query .= " `{$condition['field']}` = '{$condition['value']}'";
      }
    }

    $result = $this->database->query($query);

    $row = $result->fetch_object();

    return (!empty($row)) ? $row->count : 0;
  }

  /**
   * @since 0.04
   */
  public function delete_object($class, $id)
  {
    $db_table = $class->get_db_table();
    $primary_key = $class->get_primary_key();

    $query = "DELETE FROM `{$db_table}` WHERE `{$primary_key}` = ?";

    $result = $this->database->query($query, [$id]);

    return $result;
  }

  /**
   * TODO
   *
   * @since 0.07
   */
  public function validate_object_field(TCObject $object, $field)
  {
    $valid_db_fields = $object->get_db_fields();

    foreach ($valid_db_fields as $valid_field) {
      if ($field == $valid_field) {
        return true;
      }
    }

    return false;
  }
}
