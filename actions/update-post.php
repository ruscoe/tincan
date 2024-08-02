<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can update post handler.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$page_number = filter_input(INPUT_POST, 'page_number', FILTER_SANITIZE_NUMBER_INT);
$post_id = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);

$controller = new TCPostController();

$controller->authenticate_user();

$updated_post = null;
if ($controller->can_update_post($post_id)) {
    $updated_post = $controller->update_post($post_id, $post_content);
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to their updated post.
    $destination = TCURL::create_url(
        $controller->get_setting('page_thread'),
        [
        'thread' => $updated_post->thread_id,
        'start_at' => $page_number,
        ]
    );

    $destination .= '#post-'.$updated_post->post_id;
} else {
    // Send user back to the new post page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_edit_post'),
        [
        'post' => $updated_post->post_id,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
