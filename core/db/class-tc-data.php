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

    $query = "SELECT `setting_name`, `type`, `value` FROM `{$db_table}`";

    try {
      $result = $this->database->query($query);
    } catch (TCException $e) {
      throw new TCException('Unable to load forum settings.');
    }

    while ($object = $result->fetch_object()) {
      if ('bool' == $object->type) {
        $settings[$object->setting_name] = ('true' == $object->value);
      } else {
        $settings[$object->setting_name] = $object->value;
      }
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
   * @since 0.12
   */
  public function get_indexed_objects($class, $index_field, $ids = [], $conditions = [], $order = [], $offset = 0, $limit = 0)
  {
    $objects = $this->load_objects($class, $ids, $conditions, $order, $offset, $limit);

    // Check indexed field exists on the object.
    $db_fields = $class->get_db_fields();
    // If not a field, could be the primary key.
    $db_fields[] = $class->get_primary_key();

    if (!in_array($index_field, $db_fields)) {
      throw new TCException('Attempting to index objects by a field that doesn\'t exist: '.$index_field);
    }

    $indexed_objects = [];

    foreach ($objects as $object) {
      $indexed_objects[$object->$index_field] = $object;
    }

    return $indexed_objects;
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
   * Compares a field name to the fields of a given object.
   *
   * @since 0.07
   *
   * @param string $field
   *
   * @return bool if the field is valid
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

  /**
   * Gets an array of posts by a given user, ordered by most recent first.
   *
   * @since 0.10
   *
   * @param int $user_id the user to retrieve the posts of
   * @param int $offset  the number of posts to skip
   * @param int $limit   the number of posts to retrieve
   *
   * @return array of TCPost objects
   */
  public function get_user_posts($user_id, $offset = 0, $limit = 5)
  {
    $conditions = [
      [
        'field' => 'user_id',
        'value' => $user_id,
      ],
    ];

    $order = [
      'field' => 'post_id',
      'direction' => 'DESC',
    ];

    return $this->load_objects(new TCPost(), [], $conditions, $order, $offset, $limit);
  }
}
