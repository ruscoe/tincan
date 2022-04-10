<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.02
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
     * TODO
     *
     * @since 0.02
     */
    public function get_primary_key()
    {
        return 'role_id';
    }

    /**
     * TODO
     *
     * @since 0.02
     */
    public function get_db_table()
    {
        return 'tc_roles';
    }

    /**
     * TODO
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
