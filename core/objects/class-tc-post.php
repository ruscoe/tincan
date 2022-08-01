<?php

namespace TinCan;

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
   * Reference to TCUser::$user_id.
   *
   * @since 0.05
   */
  protected $updated_by_user;

  /**
   * Gets this post's content trimmed to a given length and adds ellipses.
   *
   * @since 0.10
   *
   * @param int $length
   *
   * @return string the trimmed content
   */
  public function get_trimmed_content($length = 64)
  {
    if (strlen($this->content) < $length) {
      return $this->content;
    } else {
      return substr($this->content, 0, $length).'...';
    }
  }

  /**
   * @see TCObject::get_parent()
   * @since 0.04
   */
  public function get_parent()
  {
    $parent = null;

    if (!empty($this->thread_id)) {
      $parent = new TCThread();
      $parent->thread_id = $this->thread_id;
    }

    return $parent;
  }

  /**
   * @see TCObject::get_name()
   * @since 0.06
   */
  public function get_name()
  {
    return 'Post '.$this->post_id;
  }

  /**
   * @see TCObject::get_slug()
   * @since 0.09
   */
  public function get_slug()
  {
    return $this->post_id;
  }

  /**
   * @see TCObject::get_primary_key()
   * @since 0.01
   */
  public function get_primary_key()
  {
    return 'post_id';
  }

  /**
   * @see TCObject::get_primary_key_value()
   * @since 0.04
   */
  public function get_primary_key_value()
  {
    return $this->post_id;
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
          'updated_by_user',
        ];
  }
}
