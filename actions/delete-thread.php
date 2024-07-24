<?php

use TinCan\controllers\TCThreadController;
use TinCan\template\TCURL;

/**
 * Tin Can delete thread handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_id = filter_input(INPUT_POST, 'board', FILTER_SANITIZE_NUMBER_INT);
$thread_id = filter_input(INPUT_POST, 'thread', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCThreadController();

if (isset($_POST['cancel'])) {
    // Cancel thread deletion and return user to the thread.
    $destination = TCURL::create_url($controller->get_setting('page_thread'), ['thread' => $thread_id]);

    header('Location: '.$destination);
    exit;
}

$controller->authenticate_user();

if ($controller->can_delete_thread($thread_id)) {
    $controller->delete_thread($thread_id);
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the confirmation page.
    $destination = TCURL::create_url(
        $controller->get_setting('page_thread_deleted'),
        [
        'board' => $board_id,
        ]
    );
} else {
    // Send user back to the delete thread page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_delete_thread'),
        [
        'thread' => $thread_id,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
