<?php
/**
 * Tin Can database layer.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

 /**
  * @since 0.01
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
        $this->database->open_connection();

        $result = $this->database->query($query);

        $this->database->close_connection();

        return $result;
    }

    /**
     * @since 0.01
     */
    public function load_settings()
    {
        $setting = new TCSetting();
        $db_table = $setting->get_db_table();

        $settings = array();

        $this->database->open_connection();

        $query = "SELECT `setting_name`, `value` FROM `{$db_table}`";

        $result = $this->database->query($query);

        while ($object = $result->fetch_object()) {
            $settings[$object->setting_name] = $object->value;
        }

        $this->database->close_connection();

        return $settings;
    }

    /**
     * @since 0.02
     */
    public function load_user($user_id)
    {
        $user = $this->load_object(new TCUser(), $user_id);

        if (!empty($user)) {
            $user->role = $this->load_object(new TCRole(), $user->role_id);
            return $user;
        }

        return null;
    }

    /**
     * @since 0.01
     */
    public function load_object($class, $id)
    {
        $db_table = $class->get_db_table();
        $primary_key = $class->get_primary_key();

        $query = "SELECT * FROM `{$db_table}` WHERE `{$primary_key}` = {$id}";

        $this->database->open_connection();

        $result = $this->database->query($query);

        if (empty($result)) {
            return null;
        }

        $row = $result->fetch_object();

        $this->database->close_connection();

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
            if (!$result) {
                throw new Exception($this->database->get_last_error());
            }

            $insert_id = $this->database->get_last_insert_id();

            $object->$primary_key = $insert_id;
        } else {
            // Existing object to update.
            $sql_fields = array();

            foreach ($db_fields as $field) {
                $sql_fields[] = "`{$field}` = '{$object->$field}'";
            }

            $query = "UPDATE `{$db_table}` SET";
            $query .= implode(',', $sql_fields);
            $query .= " WHERE `{$primary_key}` = '{$object->$primary_key}'";

            if (!$this->database->query($query)) {
                if (!$result) {
                    throw new Exception($this->database->get_last_error());
                }
            }
        }

        $this->database->close_connection();

        return $object;
    }

    /**
     * @since 0.01
     */
    public function load_objects($class, $ids = array(), $conditions = array(), $order = array(), $offset = 0, $limit = 0)
    {
        $db_table = $class->get_db_table();
        $primary_key = $class->get_primary_key();

        $query = "SELECT * FROM `{$db_table}`";

        if (!empty($ids)) {
            $ids_in = implode(',', $ids);
            $query .= " WHERE `{$primary_key}` IN ({$ids_in})";
        } elseif (!empty($conditions)) {
            $query .= " WHERE";
            foreach ($conditions as $condition) {
                // TODO: Allow conditions other than equals.
                $query .= " `{$condition['field']}` = '{$condition['value']}'";
            }
        }

        if (!empty($order)) {
            $query .= " ORDER BY {$order['field']} {$order['direction']}";
        }

        if ($limit > 0) {
            $query .=  " LIMIT {$offset}, {$limit}";
        }

        $this->database->open_connection();

        $result = $this->database->query($query);

        $objects = array();
        while ($object = $result->fetch_object()) {
            $objects[] = new $class($object);
        }

        $this->database->close_connection();

        return $objects;
    }

    /**
     * @since 0.02
     */
    public function count_objects($class, $conditions = array())
    {
        $db_table = $class->get_db_table();
        $primary_key = $class->get_primary_key();

        $query = "SELECT COUNT(*) `count` FROM `{$db_table}`";

        if (!empty($conditions)) {
            $query .= " WHERE";
            foreach ($conditions as $condition) {
                // TODO: Allow conditions other than equals.
                $query .= " `{$condition['field']}` = '{$condition['value']}'";
            }
        }

        $this->database->open_connection();

        $result = $this->database->query($query);

        $row = $result->fetch_object();

        $this->database->close_connection();

        return (!empty($row)) ? $row->count : 0;
    }
}
