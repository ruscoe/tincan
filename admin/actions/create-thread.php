<?php

use TinCan\TCData;
use TinCan\TCPost;
use TinCan\TCThread;
use TinCan\TCUser;
use TinCan\TCUserSession;

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

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
  // Not an admin user; redirect to log in page.
  header('Location: /index.php?page='.$settings['page_log_in']);
  exit;
}

$thread = new TCThread();

// Populate fields.
$db_fields = $thread->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $thread->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

$thread->first_post_id = 0;
$thread->created_by_user = $user->user_id;
$thread->updated_by_user = $user->user_id;
$thread->created_time = time();
$thread->updated_time = time();

$saved_thread = $db->save_object($thread);

// Create the initial post.
$post = new TCPost();
$post->thread_id = $saved_thread->thread_id;
$post->user_id = $saved_thread->created_by_user;
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
