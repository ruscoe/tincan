<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can log in handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$controller->log_in($username, $password);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the forum homepage.
    $destination = TCURL::create_url(null);
} else {
    // Send user back to the log in page with an error.
    $destination = TCURL::create_url($controller->get_setting('page_log_in'), ['error' => $controller->get_error()]);
}

header('Location: '.$destination);
exit;
