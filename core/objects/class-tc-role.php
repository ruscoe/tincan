<?php
/**
 * Represents a user role.
 *
 * @package Tin Can Forum
 * @since 0.02
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCRole extends TCObject
{
    /**
     * @since 0.02
     */
    public $role_id;

    /**
     * @since 0.02
     */
    protected $role_name;

    /**
     * @since 0.02
     */
    protected $allowed_actions;

    /**
     * @see TCObject::get_primary_key()
     *
     * @since 0.02
     */
    public function get_primary_key()
    {
        return 'role_id';
    }

    /**
     * @see TCObject::get_db_table()
     *
     * @since 0.02
     */
    public function get_db_table()
    {
        return 'tc_roles';
    }

    /**
     * @see TCObject::get_db_fields()
     *
     * @since 0.02
     */
    public function get_db_fields()
    {
        return array(
          'role_name',
          'allowed_actions'
        );
    }
}
