<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';

require 'class-tc-json-response.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$thread_post = filter_input(INPUT_POST, 'thread_post', FILTER_SANITIZE_STRING);

$response = new TCJSONResponse();

$db = new TCData();

// TODO: Replace with current user.
$user = $db->load_object(new TCUser(), 1);

// Validate the board this thread is intended for.
if (!$db->load_object(new TCBoard(), $board_id)) {
  $response->message = 'New thread cannot be created on this board.';
  exit($response->get_output());
}

// TODO: Validate post content.

$thread = new TCThread();
$thread->board_id = $board_id;
$thread->thread_title = $thread_title;
$thread->created_by_user = $user->user_id;
$thread->updated_by_user = $user->user_id;
$thread->created_time = time();
$thread->updated_time = time();

$new_thread = $db->save_object($thread);

if (!empty($new_thread)) {
  // Create the thread's initial post.
  $post = new TCPost();
  $post->user_id = $user->user_id;
  $post->thread_id = $new_thread->thread_id;
  $post->created_time = time();
  $post->updated_time = time();

  $new_post = $db->save_object($post);

  // TODO: Delete thread and exit with error if post cannot be created.
}
else {
  $response->message = 'New thread cannot be created.';
  exit($response->get_output());
}

$response->success = true;
exit($response->get_output());
