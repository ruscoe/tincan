<?php

namespace TinCan\db;

use TinCan\TCException;
use TinCan\db\TCMySQL;
use TinCan\objects\TCSetting;
use TinCan\objects\TCUser;
use TinCan\objects\TCRole;
use TinCan\objects\TCObject;
use TinCan\objects\TCPost;

/**
 * Tin Can database layer.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
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
        $db_host = getenv('TC_DB_HOST');
        $db_user = getenv('TC_DB_USER');
        $db_pass = getenv('TC_DB_PASS');
        $db_name = getenv('TC_DB_NAME');
        $db_port = getenv('TC_DB_PORT');

        $this->database = new TCMySQL($db_host, $db_user, $db_pass, $db_name, $db_port);
    }

    /**
     * @since 0.13
     *
     * @return bool true if a database connection is made
     */
    public function test_connection()
    {
        try {
            $this->database->open_connection();
        } catch (TCException $e) {
            return false;
        }

        return true;
    }

    /**
     * Runs a database query.
     *
     * @since 0.01
     *
     * @param  string $query the query string
     * @return object the query result object
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
     * Gets the forum's settings.
     *
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

        if (null === $result) {
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
     * Gets a user by ID.
     *
     * @since 0.02
     *
     * @param  int $user_id the user's ID
     * @return TCUser the user object
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
     * Gets an populated object by class and ID.
     *
     * @since 0.01
     *
     * @param object $class the class name
     * @param int    $id    the object ID
     *
     * @return object the populated object
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
     * Creates a new or updates an existing object.
     *
     * @since 0.01
     *
     * @param  TCObject $object the object to save
     * @return TCObject the saved object
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
            $sql_params = [];

            foreach ($db_fields as $field) {
                $sql_field_names .= "`{$field}`,";
                $sql_field_values .= "?,";
                $sql_params[] = (null !== $object->$field) ? $object->$field : '';
            }

            $sql_field_names = substr($sql_field_names, 0, -1);
            $sql_field_values = substr($sql_field_values, 0, -1);

            $query = "INSERT INTO `{$db_table}` ({$sql_field_names}) VALUES ({$sql_field_values})";

            $insert_id = $this->database->query($query, $sql_params);

            $object->$primary_key = $insert_id;
        } else {
            // Existing object to update.
            $sql_fields = [];
            $sql_params = [];

            foreach ($db_fields as $field) {
                $sql_fields[] = "`{$field}` = ?";
                $sql_params[] = (null !== $object->$field) ? $object->$field : '';
            }

            $query = "UPDATE `{$db_table}` SET";
            $query .= implode(',', $sql_fields);
            $query .= " WHERE `{$primary_key}` = ?";

            $sql_params[] = $object->$primary_key;

            $result = $this->database->query($query, $sql_params);
        }

        return $object;
    }

    /**
     * Gets multiple objects using IDs or conditions.
     *
     * @since 0.01
     *
     * @param object $class      the class name of the objects
     * @param array  $ids        the IDs of the objects
     * @param array  $conditions associative array of database fields and values to match.
     *                           Example: [ [ 'field' => 'role_id', 'value' => 1 ] ]
     * @param array  $order      associative array of order conditions
     *                           Example: [ [ 'field' => 'created_time',
     *                           'direction' => 'ASC' ] ]
     * @param int    $offset     the number of records to skip before returning results
     * @param int    $limit      the maximum number of records
     *
     * @return array an array of matching objects
     */
    public function load_objects($class, $ids = [], $conditions = [], $order = [], $offset = 0, $limit = 0)
    {
        $db_table = $class->get_db_table();
        $primary_key = $class->get_primary_key();

        $sql_params = [];

        $query = "SELECT * FROM `{$db_table}`";

        if (!empty($ids)) {
            $ids_in = implode(',', $ids);
            $query .= " WHERE `{$primary_key}` IN ({$ids_in})";
        } elseif (!empty($conditions)) {
            $query .= ' WHERE';
            foreach ($conditions as $index => $condition) {
                if (!$this->validate_object_field($class, $condition['field'])) {
                    throw new TCException('Invalid field '.$db_table.'.'.$condition['field']);
                }
                // TODO: Allow conditions other than equals.
                if ($index > 0) {
                    $query .= ' AND';
                }
                $query .= " `{$condition['field']}` = ?";

                $sql_params[] = $condition['value'];
            }
        }

        if (!empty($order)) {
            $query .= ' ORDER BY ';

            foreach ($order as $properties) {
                $query .= " {$properties['field']} {$properties['direction']},";
            }

            $query = rtrim($query, ',');
        }

        if ($limit > 0) {
            $query .= " LIMIT {$offset}, {$limit}";
        }

        $result = $this->database->query($query, $sql_params);

        $objects = [];

        if ($result) {
            while ($object = $result->fetch_object()) {
                $objects[] = new $class($object);
            }
        }

        return $objects;
    }

    /**
     * Gets multiple objects using IDs or conditions indexed by a given field.
     *
     * @since 0.12
     *
     * @param object $class       the class name of the objects
     * @param string $index_field the name of the field to index objects by
     * @param array  $ids         the IDs of the objects
     * @param array  $conditions  associative array of database fields and values to match.
     *                            Example: [ [ 'field' => 'role_id', 'value' => 1 ] ]
     * @param array  $order       associative array of order conditions
     *                            Example: [ [ 'field' => 'created_time',
     *                            'direction' => 'ASC' ] ]
     * @param int    $offset      the number of records to skip before returning results
     * @param int    $limit       the maximum number of records
     *
     * @return array an associative array of matching objects indexed by $index_field
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
     * Gets the total number of objects matching given conditions.
     *
     * @since 0.02
     *
     * @param object $class      the class name of the objects
     * @param array  $conditions associative array of database fields and values to match.
     *                           Example: [ [ 'field' => 'role_id', 'value' => 1 ] ]
     *
     * @return int the total number of objects
     */
    public function count_objects($class, $conditions = [])
    {
        $db_table = $class->get_db_table();
        $primary_key = $class->get_primary_key();

        $sql_params = [];

        $query = "SELECT COUNT(*) `count` FROM `{$db_table}`";

        if (!empty($conditions)) {
            $query .= ' WHERE';
            foreach ($conditions as $index => $condition) {
                // TODO: Allow conditions other than equals.
                if ($index > 0) {
                    $query .= ' AND';
                }
                $query .= " `{$condition['field']}` = ?";

                $sql_params[] = $condition['value'];
            }
        }

        $result = $this->database->query($query, $sql_params);

        $row = $result->fetch_object();

        return (!empty($row)) ? $row->count : 0;
    }

    /**
     * Deletes an object.
     *
     * @since 0.04
     *
     * @param object $class the class name of the object
     * @param int    $id    the ID of the object
     *
     * @return object the database query result
     */
    public function delete_object($class, $id)
    {
        if (empty($class) || empty($id)) {
            return null;
        }

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
          [
            'field' => 'post_id',
            'direction' => 'DESC',
          ],
        ];

        return $this->load_objects(new TCPost(), [], $conditions, $order, $offset, $limit);
    }

    public function get_post_page_in_thread($thread_id, $post_id, $posts_per_page)
    {
        $conditions = [
          [
            'field' => 'thread_id',
            'value' => $thread_id,
          ],
        ];

        $order = [
          [
            'field' => 'post_id',
            'direction' => 'ASC',
          ],
        ];

        $posts = $this->load_objects(new TCPost(), [], $conditions, $order);

        $position_in_thread = 1;
        foreach ($posts as $post) {
            if ($post->post_id == $post_id) {
                return ceil($position_in_thread / $posts_per_page);
            }

            $position_in_thread++;
        }

        return 0;
    }

    /**
     * Clears the banned IP address list.
     *
     * @return bool true if the query was successful
     *
     * @since 1.0.0
     */
    public function clear_banned_ips()
    {
        $query = "TRUNCATE `tc_banned_ips`";

        $result = $this->database->query($query);

        return $result;
    }

    /**
     * Clears the banned email address list.
     *
     * @return bool true if the query was successful
     *
     * @since 1.0.0
     */
    public function clear_banned_emails()
    {
        $query = "TRUNCATE `tc_banned_emails`";

        $result = $this->database->query($query);

        return $result;
    }
}
