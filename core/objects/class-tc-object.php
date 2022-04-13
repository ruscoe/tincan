<?php
/**
 * Base object for database records.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

abstract class TCObject
{
    public const ERR_NOT_FOUND = 'noobj';
    public const ERR_NOT_SAVED = 'nosave';
    public const ERR_EMPTY_FIELD = 'empty';

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
            if ($this->validate_field_value($field, $object->$field)) {
                $this->$field = $object->$field;
            }
        }
    }

    /**
     * Determines if a value can be a assigned to a field.
     * Override for object-specific validation.
     *
     * @since 0.01
     *
     * @return bool true if the field value is valid
     */
    public function validate_field_value($field_name, $value)
    {
        return (!empty($value));
    }

    /**
     * Gets the name of this objects primary key used by the database.
     *
     * @since 0.01
     *
     * @return string the primary key name
     */
    abstract public function get_primary_key();

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
}
