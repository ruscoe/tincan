<?php

use TinCan\TCData;
use TinCan\TCObject;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can user creation handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';

$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

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

$user = new TCUser();

// Validate username.
if (!$user->validate_username($username)) {
    $error = TCUser::ERR_USER;
}
// Validate email.
if (empty($error) && !$user->validate_email($email)) {
    $error = TCUser::ERR_EMAIL;
}
// Validate password.
if (empty($error) && !$user->validate_password($password)) {
    $error = TCUser::ERR_PASSWORD;
}

// Check for existing username / email.
if (empty($error)) {
    $existing_user = $db->load_objects($user, [], [['field' => 'username', 'value' => $username]]);

    if (!empty($existing_user)) {
        $error = TCUser::ERR_USERNAME_EXISTS;
    }
}

if (empty($error)) {
    $existing_user = $db->load_objects($user, [], [['field' => 'email', 'value' => $email]]);

    if (!empty($existing_user)) {
        $error = TCUser::ERR_EMAIL_EXISTS;
    }
}

$saved_user = null;

if (empty($error)) {
    $user->username = $username;
    $user->email = $email;
    $user->password = $user->get_password_hash($password);
    $user->role_id = $role_id;
    $user->suspended = 0;
    $user->created_time = time();
    $user->updated_time = time();

    $saved_user = $db->save_object($user);

    // Verify user has been created.
    if (empty($saved_user)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the users page.
$destination = '/admin/index.php?page='.$settings['admin_page_users'].'&error='.$error;
header('Location: '.$destination);
exit;
