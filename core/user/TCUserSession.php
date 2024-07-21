<?php

namespace TinCan\user;

use TinCan\db\TCData;
use TinCan\objects\TCSession;
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
        } elseif (!empty($_COOKIE['session'])) {
            $session_hash = $_COOKIE['session'];

            // Load the user's session from the database.
            $db = new TCData();
            $results = $db->load_objects(new TCSession(), [], [['field' => 'hash', 'value' => $session_hash]]);

            if (!empty($results)) {
                $session = reset($results);

                $_SESSION['user_id'] = $session->user_id;
                $this->user_id = $session->user_id;
            }
        }
    }

    public function create_session(TCUser $user)
    {
        $this->clear_existing_sessions($user->user_id);

        session_start();

        // Create a session in the database.
        $session = new TCSession();
        $session->user_id = $user->user_id;
        $session->hash = $session->generate_random_hash();
        $session->created_time = time();
        // Set the session expiration time to 30 days.
        $session->expiration_time = time() + (60 * 60 * 24 * 30);

        $db = new TCData();
        $db->save_object($session);

        $_SESSION['user_id'] = $user->user_id;

        setcookie('session', $session->get_hash(), time() + (60 * 60 * 24 * 30), '/');
    }

    public function destroy_session()
    {
        $this->clear_existing_sessions($this->user_id);

        // Clear the cookie.
        setcookie('session', '', time() - 3600, '/');

        session_destroy();
    }

    private function clear_existing_sessions($user_id)
    {
        $db = new TCData();
        $results = $db->load_objects(new TCSession(), [], [['field' => 'user_id', 'value' => $user_id]]);

        foreach ($results as $session) {
            $db->delete_object($session, $session->session_id);
        }
    }
}
