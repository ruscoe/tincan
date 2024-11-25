<?php

namespace TinCan\objects;

/**
 * Represents an image attachment.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   1.0.0
 */
class TCAttachment extends TCObject
{
    /**
     * @since 1.0.0
     */
    public $attachment_id;

    /**
     * @since 1.0.0
     */
    protected $post_id;

    /**
     * @since 1.0.0
     */
    protected $file_path;

    /**
     * @since 1.0.0
     */
    protected $thumbnail_file_path;

    /**
     * @see   TCObject::get_primary_key()
     * @since 1.0.0
     */
    public function get_primary_key()
    {
        return 'attachment_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 1.0.0
     */
    public function get_primary_key_value()
    {
        return $this->attachment_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 1.0.0
     */
    public function get_db_table()
    {
        return 'tc_attachments';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 1.0.0
     */
    public function get_db_fields()
    {
        return [
            'post_id',
            'file_path',
            'thumbnail_file_path',
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
