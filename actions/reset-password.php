<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can password reset handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
// Composer autoload.
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$controller->reset_password($email);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the reset password page with a success message.
    $destination = TCURL::create_url($controller->get_setting('page_reset_password'), ['status' => 'sent']);
} else {
    // Send user back to the reset password page with an error.
    $destination = TCURL::create_url($controller->get_setting('page_reset_password'), ['error' => $controller->get_error()]);
}

header('Location: '.$destination);
exit;
