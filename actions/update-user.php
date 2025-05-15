<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can update user handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$user_id = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$current_pass = filter_input(INPUT_POST, 'current_pass', FILTER_SANITIZE_STRING);
$new_pass = filter_input(INPUT_POST, 'new_pass', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$controller->authenticate_user();

if ($controller->can_edit_user($user_id)) {
    if (!empty($current_pass) && !empty($new_pass)) {
        if ($controller->validate_password_change($user_id, $current_pass, $new_pass)) {
            $controller->edit_user($user_id, null, $email, null, null, $new_pass);
        }
    } else {
        $controller->edit_user($user_id, null, $email, null, null, null);
    }
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to their user page.
    $destination = TCURL::create_url(
        $controller->get_setting('page_user'),
        [
        'user' => $user_id,
        ]
    );
} else {
    // Send user back to the edit user page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_edit_user'),
        [
        'user' => $user_id,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
