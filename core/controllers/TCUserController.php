<?php

namespace TinCan\controllers;

use TinCan\TCMailer;
use TinCan\controllers\TCController;
use TinCan\objects\TCMailTemplate;
use TinCan\objects\TCObject;
use TinCan\objects\TCPendingUser;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * User controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCUserController extends TCController
{
    /**
     * Logs a user in.
     *
     * @param string $username The username.
     * @param string $password The password.
     *
     * @return bool True if the user is logged in, false if not.
     *
     * @since 0.16
     */
    public function log_in($username, $password)
    {
        $user = null;

        // Find user with matching username.
        $user_results = $this->db->load_objects(new TCUser(), [], [['field' => 'username', 'value' => $username]]);
        if (!empty($user_results)) {
            $user = reset($user_results);
        }

        if (empty($user) || !$user->can_perform_action(TCUser::ACT_LOG_IN)) {
            $this->error = TCUser::ERR_NOT_FOUND;
            return false;
        }

        // Check for pending user.
        $pending_user_results = $this->db->load_objects(new TCPendingUser(), [], [['field' => 'user_id', 'value' => $user->user_id]]);
        if (!empty($pending_user_results)) {
            // Pending users cannot log in until the account is confirmed.
            $this->error = TCUser::ERR_NOT_FOUND;
            return false;
        }

        // Check password.
        if (!$user->verify_password_hash($password, $user->password)) {
            $this->error = TCUser::ERR_NOT_FOUND;
            return false;
        }

        // Successfully logged in. Create the user's session.
        $session = new TCUserSession();
        $session->create_session($user);

        return true;
    }

    /**
     * Logs a user out.
     */
    public function log_out()
    {
        // Destroy the user's session. Goodbye.
        $session = new TCUserSession();
        $session->start_session();
        $session->destroy_session();
    }

    /**
     * Determines if a user can be created.
     *
     * @param string $username The username.
     * @param string $email    The email address.
     * @param string $password The password.
     *
     * @return bool True if the user can be created, false if not.
     *
     * @since 0.16
     */
    public function can_create_user($username, $email, $password)
    {
        $user = new TCUser();

        if (!$this->settings['allow_registration']) {
            $error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        // Validate username.
        if (!$user->validate_username($username)) {
            $this->error = TCUser::ERR_USER;
            return false;
        }

        // Validate email.
        if (!$user->validate_email($email)) {
            $this->error = TCUser::ERR_EMAIL;
            return false;
        }

        // Validate password.
        if (!$user->validate_password($password)) {
            $this->error = TCUser::ERR_PASSWORD;
            return false;
        }

        // Check for existing username / email.
        $existing_user = $this->db->load_objects($user, [], [['field' => 'username', 'value' => $username]]);

        if (!empty($existing_user)) {
            $this->error = TCUser::ERR_USERNAME_EXISTS;
            return false;
        }

        $existing_user = $this->db->load_objects($user, [], [['field' => 'email', 'value' => $email]]);

        if (!empty($existing_user)) {
            $this->error = TCUser::ERR_EMAIL_EXISTS;
            return false;
        }

        return true;
    }

    /**
     * Create a new user.
     *
     * @param string $username The username.
     * @param string $email    The email address.
     * @param string $password The password.
     *
     * @return TCUser|bool The new user object or false on failure.
     *
     * @since 0.16
     */
    public function create_user($username, $email, $password)
    {
        $user = new TCUser();

        $user->username = $username;
        $user->email = $email;
        $user->password = $user->get_password_hash($password);
        $user->role_id = $this->settings['default_user_role'];
        $user->suspended = 0;
        $user->created_time = time();
        $user->updated_time = time();

        $new_user = null;

        try {
            $new_user = $this->db->save_object($user);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $new_user;
    }

    /**
     * Creates a new pending user.
     *
     * @param TCUser $user The user to create a pending user for.
     *
     * @return TCPendingUser|bool The new pending user object or false on failure.
     *
     * @since 0.16
     */
    public function create_pending_user(TCUser $user)
    {
        $pending_user = new TCPendingUser();
        $pending_user->user_id = $user->user_id;
        $pending_user->confirmation_code = $pending_user->generate_confirmation_code();

        $new_pending_user = null;

        try {
            $new_pending_user = $this->db->save_object($pending_user);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $new_pending_user;
    }

    /**
     * Confirms a user's account.
     *
     * @param string $code The confirmation code.
     *
     * @return TCUser|bool The user object if successful, otherwise FALSE.
     *
     * @since 0.16
     */
    public function confirm_account($code)
    {
        $pending_user = new TCPendingUser();

        $pending_results = $this->db->load_objects($pending_user, [], [['field' => 'confirmation_code', 'value' => $code]]);

        if (!empty($pending_results)) {
            $pending_user = reset($pending_results);
        } else {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $user = $this->db->load_user($pending_user->user_id);

        if (empty($user)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Successfully confirmed account. Create the user's session.
        $session = new TCUserSession();
        $session->create_session($user);

        // Delete the pending user record.
        $this->db->delete_object($pending_user, $pending_user->pending_user_id);

        return $user;
    }

    /**
     * Sends account confirmation email to the user.
     *
     * @param TCUser         $user          The user to send the email to.
     * @param TCPendingUser  $pending_user  The pending user object.
     *
     *  @since 0.16
     */
    public function send_pending_user_mail(TCUser $user, TCPendingUser $pending_user)
    {
        $confirmation_url = $this->settings['base_url'].'/actions/confirm-account.php?code='.$pending_user->confirmation_code;

        // Send confirmation code to the user.
        $mailer = new TCMailer($this->settings);

        // Load email template.
        // TODO: Error handling.
        $mail_template = $this->db->load_object(new TCMailTemplate(), $this->settings['mail_confirm_account']);
        $mail_subject = $mail_template->mail_template_name;
        $mail_content = $mailer->tokenize_template($mail_template, ['url' => $confirmation_url]);

        $recipients = [
          ['name' => $user->username, 'email' => $user->email],
        ];

        $mailer->send_mail(
            $this->settings['site_email_name'],
            $this->settings['site_email_address'],
            $mail_subject,
            $mail_content,
            $recipients
        );
    }
}
