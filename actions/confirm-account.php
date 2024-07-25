<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can confirm account handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

$controller = new TCUserController();

$user = $controller->confirm_account($code);

$destination = '';

// Send user to the confirm account page.
$destination = TCURL::create_url($controller->get_setting('page_confirm_account'), ['error' => $controller->get_error()]);

header('Location: '.$destination);
exit;
