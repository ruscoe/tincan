<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
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
     * TODO
     *
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'page_id';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_pages';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_fields()
    {
        return array(
      'page_title',
      'template',
      'created_time',
      'updated_time'
    );
    }
}
