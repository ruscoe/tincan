<?php
/**
 * Tin Can create thread handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-content.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require 'class-tc-json-response.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to create a new thread.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
  $errors['user'] = TCUser::ERR_NOT_AUTHORIZED;
}

// Check this thread can be created in the given board.
if (empty($errors)) {
  $board = (!empty($board_id)) ? $db->load_object(new TCBoard(), $board_id) : null;

  if (empty($board)) {
    // Board doesn't exist.
    $errors['board'] = TCObject::ERR_NOT_SAVED;
  }
}

$settings = $db->load_settings();

// Validate thread title.
$thread_title = trim($thread_title);

if (empty($thread_title) || (strlen($thread_title) < $settings['min_thread_title'])) {
  $errors['thread'] = TCObject::ERR_NOT_SAVED;
}

// Validate post content.
$post_sanitizer = new TCPostSanitizer();
$post_content = $post_sanitizer->sanitize_post($post_content);

if (empty($post_content)) {
  $errors['post'] = TCObject::ERR_NOT_SAVED;
}

$new_thread = null;

if (empty($errors)) {
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
    $post->content = $post_content;
    $post->created_time = time();
    $post->updated_time = time();

    $new_post = $db->save_object($post);

    // Delete thread and exit with error if post cannot be created.
    if (empty($new_post)) {
      $errors['thread'] = TCObject::ERR_NOT_SAVED;
      $db->delete_object($thread, $thread->thread_id);
    }
  }
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($errors));
  $response->errors = $errors;

  exit($response->get_output());
} else {
  $destination = '/index.php';

  if (empty($errors)) {
    // Send user to their new thread.
    $destination .= '?page='.$settings['page_thread'].'&thread='.$new_thread->thread_id;
  } else {
    // Send user back to the new thread page with an error.
    $destination .= '?page='.$settings['page_new_thread'].'&board='.$board_id;
    // TODO: Create a utility class for this.
    foreach ($errors as $name => $value) {
      $destination .= "&{$name}={$value}";
    }
  }

  header('Location: '.$destination);
  exit;
}
