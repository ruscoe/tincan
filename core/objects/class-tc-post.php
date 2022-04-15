<?php
/**
 * Represents a forum post.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCPost extends TCObject
{
  /**
   * @since 0.01
   */
  public $post_id;

  /**
   * Reference to TCUser::$user_id.
   *
   * @since 0.01
   */
  protected $user_id;

  /**
   * Reference to TCThread::$thread_id.
   *
   * @since 0.01
   */
  protected $thread_id;

  /**
   * @since 0.01
   */
  protected $content;

  /**
   * @since 0.01
   */
  protected $created_time;

  /**
   * @since 0.01
   */
  protected $updated_time;

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'post_id';
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.01
   */
  public function get_db_table()
  {
    return 'tc_posts';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.01
   */
  public function get_db_fields()
  {
    return [
          'user_id',
          'thread_id',
          'content',
          'created_time',
          'updated_time',
        ];
  }
}
