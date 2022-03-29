<?php

require '../tc-config.php';

require '../includes/include-db.php';
require '../includes/include-objects.php';
require '../includes/include-template.php';

require 'class-tc-json-response.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$thread_post = filter_input(INPUT_POST, 'thread_post', FILTER_SANITIZE_STRING);

$response = new TCJSONResponse();

$db = new TCData();

// TODO: Validate user.

// Validate the board this thread is intended for.
if (!$db->load_object(new TCBoard(), $board_id)) {
  $response->message = 'New thread cannot be created on this board.';
  exit($response->get_output());
}

// TODO: Validate post content.

$thread = new TCThread();
$thread->board_id = $board_id;
$thread->thread_title = $thread_title;

$new_thread = $db->save_object($thread);

if (!empty($new_thread)) {
  // Create the thread's initial post.
  $post = new TCPost();
  $post->user_id = 1;
  $post->thread_id = $new_thread->thread_id;

  $new_post = $db->save_object($post);

  // TODO: Delete thread and exit with error if post cannot be created.
}
else {
  $response->message = 'New thread cannot be created.';
  exit($response->get_output());
}

$response->success = TRUE;
exit($response->get_output());
