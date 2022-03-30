<?php
/**
 * User sesson management.
 *
 * @package Tin Can
 * @since 0.01
 */

class TCUserSession {

  /**
   * @since 0.01
   */
  public $user_id;

  public function start_session() {
    session_start();

    if (!empty($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
    }
  }

  public function create_session(TCUser $user) {
    session_start();

    $_SESSION['user_id'] = $user->user_id;
  }

  public function destroy_session() {
    session_destroy();
  }

}
