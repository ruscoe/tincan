<?php

namespace TinCan\objects;

/**
 * Represents a forum page.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCPage extends TCObject
{
    /**
     * @since 0.01
     */
    public $page_id;

    /**
     * @since 0.01
     */
    protected $page_title;

    /**
     * @since 0.01
     */
    protected $template;

    /**
     * @since 0.01
     */
    protected $created_time;

    /**
     * @since 0.01
     */
    protected $updated_time;

    /**
     * @since 0.09
     */
    protected $required;

    /**
     * @since 0.09
     */
    public function is_required()
    {
        return 1 == $this->required;
    }

    /**
     * @see   TCObject::get_name()
     * @since 0.06
     */
    public function get_name()
    {
        return $this->page_title;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'page_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.04
     */
    public function get_primary_key_value()
    {
        return $this->page_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_pages';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.01
     */
    public function get_db_fields()
    {
        return [
              'page_title',
              'template',
              'created_time',
              'updated_time',
              'required',
            ];
    }

    /**
     * @see   TCObject::get_db_relationships()
     * @since 0.15
     */
    public function get_db_relationships()
    {
        return [];
    }
}
