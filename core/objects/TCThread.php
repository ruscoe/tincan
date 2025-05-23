<?php

namespace TinCan\objects;

use TinCan\db\TCDB;

/**
 * Represents a forum thread.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCThread extends TCObject
{
    public const ERR_TITLE_SHORT = 'title_short';
    public const ERR_TITLE_LONG = 'title_long';

    /**
     * @since 0.01
     */
    public $thread_id;

    /**
     * Reference to TCBoard::$board_id.
     *
     * @since 0.01
     */
    protected $board_id;

    /**
     * @since 0.01
     */
    protected $thread_title;

    /**
     * Reference to TCPost::$post_id.
     *
     * @since 0.06
     */
    protected $first_post_id;

    /**
     * Reference to TCUser::$user_id.
     *
     * @since 0.02
     */
    protected $created_by_user;

    /**
     * Reference to TCUser::$user_id.
     *
     * @since 0.02
     */
    protected $updated_by_user;

    /**
     * @since 0.01
     */
    protected $created_time;

    /**
     * @since 0.01
     */
    protected $updated_time;

    /**
     * @since 0.16
     */
    protected $deleted = 0;

    /**
     * @since 0.16
     */
    protected $pinned = 0;

    /**
     * @since 0.16
     */
    protected $locked = 0;

    /**
     * Determines whether a given post can be deleted.
     *
     * @since 0.06
     *
     * @param TCPost $post the post to check
     *
     * @return bool true if the post may be deleted
     */
    public function post_can_be_deleted(TCPost $post)
    {
        // The first post in a thread cannot be deleted.
        // The thread must be deleted by an Administrator.
        return $post->post_id != $this->first_post_id;
    }

    /**
     * @see   TCObject::get_parent()
     * @since 0.04
     */
    public function get_parent()
    {
        $parent = null;

        if (!empty($this->board_id)) {
            $parent = new TCBoard();
            $parent->board_id = $this->board_id;
        }

        return $parent;
    }

    /**
     * @see   TCObject::get_name()
     * @since 0.06
     */
    public function get_name()
    {
        return $this->thread_title;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'thread_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.04
     */
    public function get_primary_key_value()
    {
        return $this->thread_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_threads';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.01
     */
    public function get_db_fields()
    {
        return [
            'board_id',
            'thread_title',
            'first_post_id',
            'created_by_user',
            'updated_by_user',
            'created_time',
            'updated_time',
            'deleted',
            'pinned',
            'locked',
        ];
    }

    /**
     * @see   TCObject::get_db_relationships()
     */
    public function get_db_relationships()
    {
        return [
            'board' => [
                'type' => TCDB::DB_RELATION_MANY_TO_ONE,
                'nullable' => false,
                'field' => 'board_id',
                'class' => new TCBoard(),
            ],
            'posts' => [
                'type' => TCDB::DB_RELATION_ONE_TO_MANY,
                'field' => 'thread_id',
                'class' => new TCPost(),
            ],
            'first_post' => [
                'type' => TCDB::DB_RELATION_ONE_TO_ONE,
                'nullable' => false,
                'field' => 'first_post_id',
                'class' => new TCPost(),
            ],
            'created_by_user' => [
                'type' => TCDB::DB_RELATION_ONE_TO_ONE,
                'nullable' => false,
                'field' => 'created_by_user',
                'class' => new TCUser(),
            ],
            'updated_by_user' => [
                'type' => TCDB::DB_RELATION_ONE_TO_ONE,
                'nullable' => false,
                'field' => 'updated_by_user',
                'class' => new TCUser(),
            ],
        ];
    }
}
