<?php

namespace TinCan\objects;

use TinCan\db\TCDB;

/**
 * Represents a forum board.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCBoard extends TCObject
{
    /**
     * @since 0.01
     */
    public $board_id;

    /**
     * @since 0.01
     */
    protected $board_name;

    /**
     * Reference to TCBoardGroup::$board_group_id.
     *
     * @since 0.01
     */
    protected $board_group_id;

    /**
     * @since 0.02
     */
    protected $description;

    /**
     * @since 0.16
     */
    protected $weight = 0;

    /**
     * @since 0.01
     */
    protected $created_time;

    /**
     * @since 0.01
     */
    protected $updated_time;

    /**
     * @see   TCObject::get_name()
     * @since 0.04
     */
    public function get_name()
    {
        return $this->board_name;
    }

    /**
     * @see   TCObject::get_parent()
     * @since 0.04
     */
    public function get_parent()
    {
        $parent = null;

        if (!empty($this->board_group_id)) {
            $parent = new TCBoardGroup();
            $parent->board_group_id = $this->board_group_id;
        }

        return $parent;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'board_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.04
     */
    public function get_primary_key_value()
    {
        return $this->board_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_boards';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.01
     */
    public function get_db_fields()
    {
        return [
              'board_name',
              'board_group_id',
              'description',
              'weight',
              'created_time',
              'updated_time',
            ];
    }

    /**
     * @see   TCObject::get_db_relationships()
     */
    public function get_db_relationships()
    {
        return [
            'threads' => [
                'type' => TCDB::DB_RELATION_ONE_TO_MANY,
                'class' => new TCThread(),
                'field' => 'board_id',
            ],
            'group' => [
                'type' => TCDB::DB_RELATION_MANY_TO_ONE,
                'class' => new TCBoardGroup(),
                'nullable' => false,
                'field' => 'board_group_id',
            ],
        ];
    }
}
