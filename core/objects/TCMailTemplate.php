<?php

namespace TinCan\objects;

/**
 * Represents a mail template.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.07
 */
class TCMailTemplate extends TCObject
{
    /**
     * @since 0.07
     */
    public $mail_template_id;

    /**
     * @since 0.07
     */
    protected $mail_template_name;

    /**
     * @since 0.07
     */
    protected $content;

    /**
     * @since 0.07
     */
    protected $created_time;

    /**
     * @since 0.07
     */
    protected $updated_time;

    /**
     * @see   TCObject::get_name()
     * @since 0.07
     */
    public function get_name()
    {
        return $this->mail_template_name;
    }

    /**
     * @see   TCObject::get_primary_key()
     * @since 0.07
     */
    public function get_primary_key()
    {
        return 'mail_template_id';
    }

    /**
     * @see   TCObject::get_primary_key_value()
     * @since 0.07
     */
    public function get_primary_key_value()
    {
        return $this->mail_template_id;
    }

    /**
     * @see   TCObject::get_db_table()
     * @since 0.07
     */
    public function get_db_table()
    {
        return 'tc_mail_templates';
    }

    /**
     * @see   TCObject::get_db_fields()
     * @since 0.07
     */
    public function get_db_fields()
    {
        return [
              'mail_template_name',
              'content',
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
