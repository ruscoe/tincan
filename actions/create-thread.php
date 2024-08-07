<?php

use TinCan\controllers\TCPostController;
use TinCan\controllers\TCThreadController;
use TinCan\template\TCURL;

/**
 * Tin Can create thread handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);

$controller = new TCThreadController();

$controller->authenticate_user();

$attachments = [];

if (!empty($_FILES['attachments'])) {
    $total_files = count($_FILES['attachments']['name']);

    // Reformat the files array to individual files.
    if ($total_files > 0) {
        $attachments = [];
        for ($i = 0; $i < $total_files; $i++) {
            $file = [];
            foreach ($_FILES['attachments'] as $key => $values) {
                $file[$key] = $values[$i];
            }
            $attachments[] = $file;
        }
    }
}

$new_thread = null;
if ($controller->can_create_thread($board_id)) {
    $new_thread = $controller->create_thread($board_id, $thread_title, $post_content);

    if (!empty($attachments)) {
        $post_controller = new TCPostController();
        $post_controller->authenticate_user();

        foreach ($attachments as $file) {
            if ($post_controller->can_add_attachment($new_thread->first_post_id)) {
                $post_controller->add_attachment($new_thread->first_post_id, $file);
            }
        }

        if (!empty($post_controller->get_error())) {
            $controller->set_error($post_controller->get_error());
        }
    }
}

$destination = '';

if (empty($controller->get_error())) {
    // Send user to their new thread.
    $destination = TCURL::create_url(
        $controller->get_setting('page_thread'),
        [
        'thread' => $new_thread->thread_id,
        ]
    );
} else {
    // Send user back to the new thread page with an error.
    $destination = TCURL::create_url(
        $controller->get_setting('page_new_thread'),
        [
        'board' => $board_id,
        'error' => $controller->get_error(),
        'title' => $thread_title,
        'content' => $post_content,
        ]
    );
}

header('Location: '.$destination);
exit;
