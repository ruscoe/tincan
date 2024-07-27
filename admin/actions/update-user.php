<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can user update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$update_user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Ignore placeholder password used on form.
if ('***' == $password) {
    $password = null;
}

$suspended = ('on' === filter_input(INPUT_POST, 'suspended', FILTER_SANITIZE_STRING)) ? 1 : 0;

$controller = new TCUserController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->edit_user($update_user_id, null, $email, $username, $role_id, $password, $suspended);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the users page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_users'));
} else {
    // Send user back to the edit user page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_edit_user'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
