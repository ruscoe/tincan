<?php

namespace TinCan;

/**
 * Tin Can JSON response.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCJSONResponse
{
  /**
   * @since 0.01
   */
  public $success;

  /**
   * @since 0.01
   */
  public $message;

  /**
   * @since 0.02
   */
  public $errors;

  public function get_output()
  {
    return json_encode($this);
  }
}
