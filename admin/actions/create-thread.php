<?php

use TinCan\TCData;
use TinCan\TCPost;
use TinCan\TCThread;

// TODO: Check user role before anything else.

/**
 * Tin Can thread creation handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';

$db = new TCData();
$settings = $db->load_settings();

$thread = new TCThread();

// Populate fields.
$db_fields = $thread->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $thread->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

$thread->first_post_id = 0;
$thread->created_time = time();
$thread->updated_time = time();

$saved_thread = $db->save_object($thread);

// Create the initial post.
$post = new TCPost();
$post->thread_id = $saved_thread->thread_id;
// TODO: Initial post content.
// $post->content = $sanitizer->sanitize_post($post_content);
$post->updated_by_user = $thread->updated_by_user;
$post->created_time = time();
$post->updated_time = time();

$saved_post = $db->save_object($post);

// Assign the initial post to the new thread.
$saved_thread->first_post_id = $saved_post->post_id;
$db->save_object($saved_thread);

// Return to the threads page.
$destination = '/admin/index.php?page='.$settings['admin_page_threads'];
header('Location: '.$destination);
exit;
