<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can user deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCUserController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->delete_user($user_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the users page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_users'));
} else {
    // Send user back to the delete user page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_delete_user'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
