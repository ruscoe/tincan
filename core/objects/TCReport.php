<?php

namespace TinCan\objects;

/**
 * Represents a post report.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCReport extends TCObject
{
    /**
     * @since 0.16
     */
    public $report_id;

    /**
     * @since 0.16
     */
    protected $user_id;

    /**
     * @since 0.16
     */
    protected $post_id;

    /**
     * @since 0.16
     */
    protected $reason;

    /**
     * @since 0.16
     */
    protected $created_time;

    /**
     * @since 0.16
     */
    protected $updated_time;

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.16
     */
    public function get_primary_key()
    {
        return 'report_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.16
     */
    public function get_primary_key_value()
    {
        return $this->report_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.16
     */
    public function get_db_table()
    {
        return 'tc_reports';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.16
     */
    public function get_db_fields()
    {
        return [
            'user_id',
            'post_id',
            'reason',
            'created_time',
            'updated_time',
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
