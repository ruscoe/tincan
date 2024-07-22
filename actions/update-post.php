<?php

use TinCan\db\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\objects\TCObject;
use TinCan\objects\TCPost;
use TinCan\content\TCPostSanitizer;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can update post handler.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$page_number = filter_input(INPUT_POST, 'page_number', FILTER_SANITIZE_NUMBER_INT);
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
    $settings = $db->load_settings();
} catch (TCException $e) {
    echo $e->getMessage();
    exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

$post = $db->load_object(new TCPost(), $post_id);

// Check user has permission to edit this post.
if (empty($user) || !$user->can_edit_post($post)) {
    $error = TCUser::ERR_NOT_AUTHORIZED;
}

// Validate post content.
$post_sanitizer = new TCPostSanitizer();
$post_content = $post_sanitizer->sanitize_post($post_content);

if (empty($post_content)) {
    $error = TCObject::ERR_NOT_SAVED;
}

$sanitizer = new TCPostSanitizer();

if (empty($error)) {
    $post->content = $sanitizer->sanitize_post($post_content);
    $post->updated_time = time();
    $post->updated_by_user = $user->user_id;

    $updated_post = $db->save_object($post);

    if (empty($updated_post)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

$destination = '';

if (empty($error)) {
    // Send user to their updated post.
    $destination = TCURL::create_url(
        $settings['page_thread'],
        [
        'thread' => $post->thread_id,
        'start_at' => $page_number,
        ]
    );

    $destination .= '#post-'.$post->post_id;
}

if (!empty($error)) {
    // Send user back to the new post page with an error.
    $destination = TCURL::create_url(
        $settings['page_edit_post'],
        [
        'post' => $post->post_id,
        'error' => $error,
        ]
    );
}

header('Location: '.$destination);
exit;
