<?php
/**
 * Tin Can JSON response.
 *
 * @package Tin Can
 * @since 0.01
 */

class TCJSONResponse {

  /**
   * @since 0.01
   */
  public $success;

  /**
   * @since 0.01
   */
  public $message;

  public function get_output() {
    return json_encode($this);
  }

}
