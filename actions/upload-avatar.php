<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can update user handler.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$user_id = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);
$file = $_FILES['avatar_image'];

$controller = new TCUserController();

$controller->authenticate_user();

if ($controller->can_edit_user($user_id)) {
    $controller->edit_user($user_id, $file);
}

$destination = null;

if (empty($controller->get_error())) {
    // Send user to the updated page.
    $destination = TCURL::create_url($controller->get_setting('page_user'), ['user' => $user_id]);
} else {
    // Send user back to the page with an error.
    $destination = TCURL::create_url($controller->get_setting('page_user'), ['user' => $user_id, 'error' => $controller->get_error()]);
}

header('Location: '.$destination);
exit;
