<?php

use TinCan\controllers\TCThreadController;
use TinCan\template\TCURL;

/**
 * Tin Can thread update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = trim(filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING));
$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCThreadController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->edit_thread($thread_id, $thread_title, $board_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the threads page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_threads'));
} else {
    // Send user back to the edit thread page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_edit_thread'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
