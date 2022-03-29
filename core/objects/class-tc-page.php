<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCPage extends TCObject {

  /**
   * @since 0.01
   */
  public $page_id;

  /**
   * @since 0.01
   */
  public $page_title;

  /**
   * @since 0.01
   */
  public $template;

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_primary_key() {
    return 'page_id';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_table() {
    return 'tc_pages';
  }

  /**
   * TODO
   *
   * @since 0.01
   */
  public function get_db_fields() {
    return array(
      'page_id',
      'page_title',
      'template'
    );
  }

}
