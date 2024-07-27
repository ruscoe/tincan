<?php

namespace TinCan\controllers;

use TinCan\TCMailer;
use TinCan\content\TCImage;
use TinCan\controllers\TCController;
use TinCan\objects\TCMailTemplate;
use TinCan\objects\TCObject;
use TinCan\objects\TCPendingUser;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;
use TinCan\template\TCURL;

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
            $this->error = TCObject::ERR_NOT_SAVED;
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
     * Determines if the current user can edit another user.
     *
     * @param int $user_id The ID of the user to edit.
     *
     * @return bool True if the user can be edited, false otherwise.
     *
     * @since 0.16
     */
    public function can_edit_user($user_id)
    {
        $edit_user = $this->db->load_user($user_id);

        if (empty($edit_user)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        if (empty($this->user) || !$this->user->can_edit_user($edit_user)) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        return true;
    }

    /**
     * Edits a user.
     *
     * @param int    $user_id   The ID of the user to edit.
     * @param array  $file      The uploaded file.
     * @param string $email     The email address.
     * @param string $username  The username.
     * @param int    $role_id   The role ID.
     * @param string $password  The password.
     * @param int    $suspended The suspended status.
     *
     * @return bool True if the user has been edited, false otherwise.
     *
     * @since 0.16
     */
    public function edit_user($user_id, $file = null, $email, $username = null, $role_id = null, $password = null, $suspended = null)
    {
        $edit_user = $this->db->load_user($user_id);

        // Validate username.
        if (!$edit_user->validate_username($username)) {
            $this->error = TCUser::ERR_USER;
            return false;
        }

        // Validate email.
        if (!$edit_user->validate_email($email)) {
            $this->error = TCUser::ERR_EMAIL;
            return false;
        }

        // Validate password.
        if (($password !== null) && !$edit_user->validate_password($password)) {
            $this->error = TCUser::ERR_PASSWORD;
            return false;
        }

        // Check for existing username / email.
        $results = $this->db->load_objects($edit_user, [], [['field' => 'username', 'value' => $username]]);
        $existing_user = reset($results);

        if (!empty($existing_user) && ($existing_user->user_id != $edit_user->user_id)) {
            var_dump('hello');
            exit;
            $this->error = TCUser::ERR_USERNAME_EXISTS;
            return false;
        }

        $results = $this->db->load_objects($edit_user, [], [['field' => 'email', 'value' => $email]]);
        $existing_user = reset($results);

        if (!empty($existing_user) && ($existing_user->user_id != $edit_user->user_id)) {
            $this->error = TCUser::ERR_EMAIL_EXISTS;
            return false;
        }

        // Upload an avatar image.
        if (!empty($file)) {
            if (UPLOAD_ERR_OK !== $file['error']) {
                $this->error = TCImage::ERR_FILE_GENERAL;
                return false;
            }

            $image_data = getimagesize($file['tmp_name']);

            $image = new TCImage();
            $image->width = $image_data[0];
            $image->height = $image_data[1];
            $image->file_type = $image_data[2];
            $image->mime_type = $image_data['mime'];
            $image->file_size = $file['size'];

            // Check for valid file type.
            if (!$image->is_valid_type()) {
                $this->error = TCImage::ERR_FILE_TYPE;
                return false;
            }

            // Check file size.
            if (!$image->is_valid_size()) {
                $this->error = TCImage::ERR_FILE_SIZE;
                return false;
            }

            // Avatar filename is the user's ID followed by the file extension.
            // The directory containing the avatar file is named for the last digit of
            // the user's ID. This just allows us to split up files and avoid massive
            // directories.

            if (!is_dir(getenv('TC_UPLOADS_PATH').'/avatars')) {
                // If the avatars upload path doesn't exist, create it with write permissions.
                mkdir(getenv('TC_UPLOADS_PATH').'/avatars', 0755);
            }

            $target_path = 'avatars/'.substr($edit_user->user_id, -1);

            if (!is_dir(getenv('TC_UPLOADS_PATH').'/'.$target_path)) {
                // If the avatar upload path doesn't exist, create it with write permissions.
                mkdir(getenv('TC_UPLOADS_PATH').'/'.$target_path, 0755);
            }

            $target_file = $edit_user->user_id.'.jpg';
            $target_full_path = getenv('TC_UPLOADS_PATH').'/'.$target_path.'/'.$target_file;

            if (!move_uploaded_file($file['tmp_name'], $target_full_path)) {
                $this->error = TCImage::ERR_FILE_GENERAL;
                return false;
            }

            // Resize and crop file to a square.
            $scaled_image = $image->scale_to_square($target_full_path, 256);

            if (!imagejpeg($scaled_image, $target_full_path)) {
                $this->error = TCImage::ERR_FILE_GENERAL;
                return false;
            }

            $edit_user->avatar = '/uploads/'.$target_path.'/'.$target_file;
            $edit_user->updated_time = time();
        }

        $edit_user->email = $email;
        $edit_user->password = $edit_user->get_password_hash($password);
        $edit_user->updated_time = time();

        // Username, role ID, and suspected must be explicitly set.
        if ($username !== null) {
            $edit_user->username = $username;
        }
        if ($role_id !== null) {
            $edit_user->role_id = $role_id;
        }
        if ($suspended !== null) {
            $edit_user->suspended = $suspended;
        }

        try {
            $this->db->save_object($edit_user);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
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

    public function reset_password($email)
    {
        if (empty($email)) {
            $this->error = TCUser::ERR_NOT_FOUND;
            return false;
        }

        // Find user with matching email.
        $conditions = [
            [
            'field' => 'email',
            'value' => $email,
            ],
        ];

        $matched_user = null;

        try {
            $user_results = $this->db->load_objects(new TCUser(), [], $conditions);
            if (!empty($user_results)) {
                $matched_user = reset($user_results);
            } else {
                $this->error = TCUser::ERR_NOT_FOUND;
                return false;
            }
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $matched_user->password_reset_code = $matched_user->generate_password_reset_code();

        try {
            $this->db->save_object($matched_user);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $reset_url = $this->settings['base_url'].TCURL::create_url($this->settings['page_set_password'], ['code' => $matched_user->password_reset_code]);

        // Send password reset code to the user.
        $mailer = new TCMailer($this->settings);

        // Load email template.
        $mail_template = $this->db->load_object(new TCMailTemplate(), $this->settings['mail_reset_password']);
        $mail_subject = $mail_template->mail_template_name;
        $mail_content = $mailer->tokenize_template($mail_template, ['url' => $reset_url]);

        $recipients = [
          ['name' => $matched_user->username, 'email' => $matched_user->email],
        ];

        try {
            $mailer->send_mail(
                $this->settings['site_email_name'],
                $this->settings['site_email_address'],
                $mail_subject,
                $mail_content,
                $recipients
            );
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    /**
     * Sets a new password for a user.
     *
     * @param string $code     The password reset code.
     * @param string $password The new password.
     *
     * @return bool True if the password was set, false if not.
     *
     * @since 0.16
     */
    public function set_password($code, $password)
    {
        $user = new TCUser();

        // Validate reset code.
        if (empty($code)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Validate password.
        if (!$user->validate_password($password)) {
            $this->error = TCUser::ERR_PASSWORD;
            return false;
        }

        // Find user with matching password reset code.
        $conditions = [
            [
            'field' => 'password_reset_code',
            'value' => $code,
            ],
        ];

        $matched_user = null;

        try {
            $user_results = $this->db->load_objects(new TCUser(), [], $conditions);
            if (!empty($user_results)) {
                $matched_user = reset($user_results);
            } else {
                $this->error = TCUser::ERR_NOT_FOUND;
                return false;
            }
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $matched_user->password = $matched_user->get_password_hash($password);
        // Password has been reset, so expire the reset code.
        $matched_user->password_reset_code = '';

        try {
            $this->db->save_object($matched_user);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
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

    /**
     * Deletes a user.
     *
     * @param int $user_id The ID of the user to delete.
     *
     * @return bool True if the user has been deleted, false otherwise.
     *
     * @since 0.16
     */
    public function delete_user($user_id)
    {
        $delete_user = $this->db->load_object(new TCUser(), $user_id);

        if (empty($delete_user)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // User cannot delete their own account.
        if ($delete_user->user_id == $this->user->user_id) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        try {
            $this->db->delete_object(new TCUser(), $delete_user->user_id);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    public function upload_avatar()
    {

    }
}
