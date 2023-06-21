<?php

namespace TinCan\user;

use TinCan\objects\TCUser;

/**
 * User session management.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
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
