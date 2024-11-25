<?php

namespace TinCan\objects;

/**
 * Represents a forum setting.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCSetting extends TCObject
{
    /**
     * @since 0.01
     */
    public $setting_id;

    /**
     * @since 0.01
     */
    protected $setting_name;

    /**
     * @since 0.09
     */
    protected $category;

    /**
     * @since 0.01
     */
    protected $type;

    /**
     * @since 0.01
     */
    protected $title;

    /**
     * @since 0.01
     */
    protected $value;

    /**
     * @since 0.01
     */
    protected $required;

    /**
     * Determines if this setting is a required setting.
     *
     * @since 0.09
     *
     * @return bool true if this setting is required
     */
    public function is_required()
    {
        return 1 == $this->required;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'setting_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.04
     */
    public function get_primary_key_value()
    {
        return $this->setting_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_settings';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.01
     */
    public function get_db_fields()
    {
        return [
              'setting_name',
              'category',
              'type',
              'title',
              'value',
              'required',
            ];
    }

    /**
     * @see   TCObject::get_db_relationships()
     */
    public function get_db_relationships()
    {
        return [];
    }
}
