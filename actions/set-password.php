<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can set password handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$controller->set_password($code, $password);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the set password page with a success message.
    $destination = TCURL::create_url($controller->get_setting('page_set_password'), ['status' => 'set']);
} else {
    // Send user back to the set password page with an error.
    $destination = TCURL::create_url($controller->get_setting('page_set_password'), ['code' => $code, 'error' => $controller->get_error()]);
}

header('Location: '.$destination);
exit;
