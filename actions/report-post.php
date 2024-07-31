<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can report post handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread', FILTER_SANITIZE_NUMBER_INT);
$post_id = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT);
$reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);

$controller = new TCPostController();

if (isset($_POST['cancel'])) {
    // Cancel report and return user to the thread.
    $destination = TCURL::create_url($controller->get_setting('page_thread'), ['thread' => $thread_id]);

    header('Location: '.$destination);
    exit;
}

$controller->authenticate_user();

if ($controller->can_report_post($post_id)) {
    $controller->report_post($post_id, $reason);
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the confirmation page.
    $destination = TCURL::create_url(
        $controller->get_setting('page_post_reported'),
        [
        'thread' => $thread_id,
        ]
    );
} else {
    // Send user back to the report post page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_report_post'),
        [
        'thread' => $thread_id,
        'post' => $post_id,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
