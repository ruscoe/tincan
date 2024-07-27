<?php

namespace TinCan\controllers;

use TinCan\TCException;
use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Base controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
abstract class TCController
{
    /**
     * The error generated during the last operation, if any.
     *
     * @var string
     *
     * @since 0.16
     */
    protected $error;

    /**
     * The database object.
     *
     * @var TCData
     *
     * @since 0.16
     */
    protected $db;

    /**
     * The forum settings.
     *
     * @var array
     *
     * @since 0.16
     */
    protected $settings;

    /**
     * The authenticated user.
     *
     * @var TCUser
     *
     * @since 0.16
     */
    protected $user;

    /**
     * @since 0.16
     */
    public function __construct()
    {
        $this->db = new TCData();

        try {
            $this->settings = $this->db->load_settings();
        } catch (TCException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Authenticates the user.
     *
     * @since 0.16
     */
    public function authenticate_user()
    {
        $session = new TCUserSession();
        $session->start_session();
        $user_id = $session->get_user_id();
        $this->user = (!empty($user_id)) ? $this->db->load_user($user_id) : null;
    }

    /**
     * Determines if the user is an admin user.
     *
     * @return bool
     *
     * @since 0.16
     */
    public function is_admin_user()
    {
        return (!empty($this->user) && $this->user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
    }

    /**
     * Gets the error generated during the last operation.
     *
     * @return string
     *
     * @since 0.16
     */
    public function get_error()
    {
        return $this->error;
    }

    /**
     * Gets a forum setting.
     *
     * @param string $setting The setting to get.
     *
     * @return mixed
     *
     * @since 0.16
     */
    public function get_setting($setting)
    {
        return $this->settings[$setting];
    }
}
