<?php

use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can user update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';


$update_user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if ('***' == $password) {
    $password = null;
}

$suspended = ('on' === filter_input(INPUT_POST, 'suspended', FILTER_SANITIZE_STRING)) ? 1 : 0;

$db = new TCData();
$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$settings['page_log_in']);
    exit;
}

$update_user = $db->load_user($update_user_id);

$error = null;

if (empty($update_user)) {
    $error = TCObject::ERR_NOT_FOUND;
}

// Validate username.
if (empty($error) && !$update_user->validate_username($username)) {
    $error = TCUser::ERR_USER;
}
// Validate email.
if (empty($error) && !$update_user->validate_email($email)) {
    $error = TCUser::ERR_EMAIL;
}
// Validate password.
if (empty($error) && (!empty($password)) && !$update_user->validate_password($password)) {
    $error = TCUser::ERR_PASSWORD;
}

// Check for unique fields belonging to other users.
$existing_user_checks = [
  [
    'field' => 'username',
    'value' => $username,
    'err_code' => TCUser::ERR_USERNAME_EXISTS,
  ],
  [
    'field' => 'email',
    'value' => $email,
    'err_code' => TCUser::ERR_EMAIL_EXISTS,
  ],
];

if (empty($error)) {
    foreach ($existing_user_checks as $user_check) {
        $existing_user = $db->load_objects($update_user, [], [['field' => $user_check['field'], 'value' => $user_check['value']]]);

        if (!empty($existing_user)) {
            if ($existing_user[0]->user_id != $update_user->user_id) {
                $error = $user_check['err_code'];
            }
        }
    }
}

$saved_user = null;

if (empty($error)) {
    $update_user->username = $username;
    $update_user->email = $email;
    $update_user->role_id = $role_id;
    $update_user->suspended = $suspended;
    $update_user->updated_time = time();

    // Users cannot suspend themselves.
    if ($user->user_id == $update_user->user_id) {
        $update_user->suspended = 0;
    }

    if (!empty($password)) {
        $update_user->password = $user->get_password_hash($password);
    }

    $saved_user = $db->save_object($update_user);

    // Verify user has been updated.
    if (empty($saved_user)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the users page.
$destination = '/admin/index.php?page='.$settings['admin_page_users'].'&error='.$error;
header('Location: '.$destination);
exit;
