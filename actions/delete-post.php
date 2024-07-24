<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can delete post handler.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread', FILTER_SANITIZE_NUMBER_INT);
$post_id = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCPostController();

if (isset($_POST['cancel'])) {
    // Cancel post deletion and return user to the thread.
    $destination = TCURL::create_url($controller->get_setting('page_thread'), ['thread' => $thread_id]);

    header('Location: '.$destination);
    exit;
}

$controller->authenticate_user();

if ($controller->can_delete_post($post_id)) {
    $controller->delete_post($post_id);
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the confirmation page.
    $destination = TCURL::create_url(
        $controller->get_setting('page_post_deleted'),
        [
        'thread' => $thread_id,
        ]
    );
} else {
    // Send user back to the delete post page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_delete_post'),
        [
        'thread' => $thread_id,
        'post' => $post_id,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
