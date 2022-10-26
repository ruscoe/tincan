<?php

namespace TinCan;

/**
 * Represents a plugin.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCPlugin extends TCObject
{
    /**
     * @since 0.14
     */
    public $plugin_id;

    /**
     * @since 0.14
     */
    protected $plugin_name;

    /**
     * @since 0.14
     */
    protected $plugin_namespace;

    /**
     * @since 0.14
     */
    protected $path;

    /**
     * @since 0.14
     */
    protected $enabled;

    /**
     * @see TCObject::get_name()
     * @since 0.14
     */
    public function get_name()
    {
        return $this->plugin_name;
    }

    /**
     * @see TCObject::get_primary_key()
     * @since 0.14
     */
    public function get_primary_key()
    {
        return 'plugin_id';
    }

    /**
     * @see TCObject::get_primary_key_value()
     * @since 0.14
     */
    public function get_primary_key_value()
    {
        return $this->plugin_id;
    }

    /**
     * @see TCObject::get_db_table()
     * @since 0.14
     */
    public function get_db_table()
    {
        return 'tc_plugins';
    }

    /**
     * @see TCObject::get_db_fields()
     * @since 0.14
     */
    public function get_db_fields()
    {
        return [
              'plugin_name',
              'plugin_namespace',
              'path',
              'enabled',
            ];
    }
}
