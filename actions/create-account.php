<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;
use TinCan\user\TCUserSession;

/**
 * Tin Can create account handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
// Composer autoload.
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$new_user = null;
if ($controller->can_create_user($username, $email, $password)) {
    $new_user = $controller->create_user($username, $email, $password);
}

$new_pending_user = null;
if (empty($controller->get_error()) && $controller->get_setting('require_confirm_email')) {
    $new_pending_user = $controller->create_pending_user($new_user);

    if (!empty($new_pending_user)) {
        $controller->send_confirmation_email($new_user, $new_pending_user);
    }
}

if (empty($controller->get_error()) && (!$controller->get_setting('require_confirm_email'))) {
    // Account confirmation not required; create the user's session.
    $session = new TCUserSession();
    $session->create_session($new_user);
}

$destination = '';

if (empty($controller->get_error())) {
    if ($settings['require_confirm_email']) {
        // Send user to the create account page with success message.
        $destination = TCURL::create_url($controller->get_setting('page_create_account'), ['status' => 'sent']);
    } else {
        // Send the user to the forum homepage.
        $destination = TCURL::create_url(null);
    }
} else {
    // Send user back to the create account page with an error.
    $destination = TCURL::create_url($controller->get_setting('page_create_account'), [
        'username' => $username,
        'email' => $email,
        'error' => $controller->get_error(),
    ]);
}

header('Location: '.$destination);
exit;
