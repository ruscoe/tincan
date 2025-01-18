<?php

namespace TinCan\objects;

use TinCan\TCException;

/**
 * Base object for database records.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
abstract class TCObject
{
    public const ERR_NOT_FOUND = 'noobj';
    public const ERR_NOT_SAVED = 'nosave';
    public const ERR_EMPTY_FIELD = 'empty';

    protected array $counts = [];

    /**
     * @since 0.01
     */
    public function __construct($object = null)
    {
        if (!empty($object)) {
            $primary_key = $this->get_primary_key();
            $this->$primary_key = (!empty($object->$primary_key)) ? $object->$primary_key : null;

            $this->populate_from_db($object);
        }
    }

    /**
     * @since 0.01
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @since 0.01
     */
    public function __set($name, $value)
    {
        if ($this->validate_field_value($name, $value)) {
            $this->$name = $value;
        }
    }

    /**
     * Populates this object's properties from a generic object.
     *
     * @since 0.01
     *
     * @param object $object an object with properties matching this object
     */
    public function populate_from_db($object)
    {
        $db_fields = $this->get_db_fields();

        foreach ($db_fields as $field) {
            if (isset($object->$field) && $this->validate_field_value($field, $object->$field)) {
                $this->$field = $object->$field;
            }
        }

        // Check for count relationship data
        $relations = $this->get_db_relationships();

        foreach (array_keys($relations) as $relation) {
            if (isset($object->{$relation . '_count'})) {
                $this->counts[$relation] = $object->{$relation . '_count'};
            }
        }
    }

    /**
     * Determines if a value can be a assigned to a field.
     * Override for object-specific validation.
     *
     * @since 0.01
     *
     * @param string $field_name the name of the field
     * @param string $value      the value to validate
     *
     * @return bool true if the field value is valid
     */
    public function validate_field_value($field_name, $value)
    {
        return true;
    }

    /**
     * Gets the name of this object.
     * Override for object-specific values.
     *
     * @since 0.04
     *
     * @return string the object name
     */
    public function get_name()
    {
        return '';
    }

    /**
     * Gets the parent object if this object references one.
     *
     * @since 0.04
     *
     * @return TCObject the parent object with primary key populated
     */
    public function get_parent()
    {
        return null;
    }

    /**
     * Gets the name of this object's primary key used by the database.
     *
     * @since 0.01
     *
     * @return string the primary key name
     */
    abstract public function get_primary_key();

    /**
     * Gets the value of this object's primary key used by the database.
     *
     * @since 0.04
     *
     * @return string the primary key value
     */
    abstract public function get_primary_key_value();

    /**
     * Gets the name of the database table used to store this object.
     *
     * @since 0.01
     *
     * @return string the database table name
     */
    abstract public function get_db_table();

    /**
     * Gets the names of the database fields that can be read and written.
     *
     * @since 0.01
     *
     * @return array the database table fields
     */
    abstract public function get_db_fields();

    /**
     * Gets the mappings between foreign keys in this object to other objects.
     *
     * @return array the database table relationships
     */
    abstract public function get_db_relationships();
}
