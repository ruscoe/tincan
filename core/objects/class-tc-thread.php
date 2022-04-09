<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCThread extends TCObject
{
  /**
   * @since 0.01
   */
    public $thread_id;

    /**
     * @since 0.01
     */
    protected $board_id;

    /**
     * @since 0.01
     */
    protected $thread_title;

    /**
     * @since 0.02
     */
    protected $created_by_user;

    /**
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
     * TODO
     *
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'thread_id';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_threads';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_fields()
    {
        return array(
      'board_id',
      'thread_title',
      'created_by_user',
      'updated_by_user',
      'created_time',
      'updated_time'
    );
    }
}
