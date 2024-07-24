<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can create post handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);

$controller = new TCPostController();

$controller->authenticate_user();

$new_post = null;
if ($controller->can_create_post($thread_id)) {
    $new_post = $controller->create_post($thread_id, $post_content);
}

// Calculate the total pages in this thread so the user can be sent
// directly to their new post.
$total_pages = $controller->get_total_thread_pages($thread_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send the user back to the thread.
    $destination = TCURL::create_url(
        $controller->get_setting('page_thread'),
        [
        'thread' => $thread_id,
        'start_at' => $total_pages,
        ]
    );

    $destination .= '#post-'.$new_post->post_id;
} else {
    // Send the user back to the thread with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_thread'),
        [
        'thread' => $thread_id,
        'start_at' => $total_pages,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
