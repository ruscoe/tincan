<?php

namespace TinCan;

/**
 * Represents a mail template.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
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
   * @see TCObject::get_name()
   * @since 0.07
   */
  public function get_name()
  {
    return $this->mail_template_name;
  }

  /**
   * @see TCObject::get_primary_key()
   * @since 0.07
   */
  public function get_primary_key()
  {
    return 'mail_template_id';
  }

  /**
   * @see TCObject::get_primary_key_value()
   * @since 0.07
   */
  public function get_primary_key_value()
  {
    return $this->mail_template_id;
  }

  /**
   * @see TCObject::get_db_table()
   * @since 0.07
   */
  public function get_db_table()
  {
    return 'tc_mail_templates';
  }

  /**
   * @see TCObject::get_db_fields()
   * @since 0.07
   */
  public function get_db_fields()
  {
    return [
          'mail_template_id',
          'mail_template_name',
          'content',
          'created_time',
          'updated_time',
        ];
  }
}
