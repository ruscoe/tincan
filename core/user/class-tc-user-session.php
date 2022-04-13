<?php
/**
 * User session management.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCUserSession
{
  /**
   * @since 0.01
   */
    public $user_id;

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function start_session()
    {
        session_start();

        if (!empty($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
        }
    }

    public function create_session(TCUser $user)
    {
        session_start();

        $_SESSION['user_id'] = $user->user_id;
    }

    public function destroy_session()
    {
        session_destroy();
    }
}
