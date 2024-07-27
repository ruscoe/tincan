<?php

use TinCan\controllers\TCThreadController;
use TinCan\template\TCURL;

/**
 * Tin Can thread deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCThreadController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->permanently_delete_thread($thread_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the threads page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_threads'));
} else {
    // Send user back to the delete thread page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_delete_thread'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
