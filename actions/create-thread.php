<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPost;
use TinCan\TCPostSanitizer;
use TinCan\TCThread;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can create thread handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-error-message.php';
require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-json-response.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-content.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();

$error = null;

try {
  $user = (!empty($user_id)) ? $db->load_user($user_id) : null;
} catch (TCException $e) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

// Check user has permission to create a new thread.
if (empty($error) && (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_THREAD))) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

// Check this thread can be created in the given board.
if (empty($error)) {
  $board = (!empty($board_id)) ? $db->load_object(new TCBoard(), $board_id) : null;

  if (empty($board)) {
    // Board doesn't exist.
    $error = TCObject::ERR_NOT_SAVED;
  }
}

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

// Validate thread title.
$thread_title = trim($thread_title);

if (empty($thread_title) || (strlen($thread_title) < $settings['min_thread_title'])) {
  $error = TCObject::ERR_NOT_SAVED;
}

// Validate post content.
$post_sanitizer = new TCPostSanitizer();
$post_content = $post_sanitizer->sanitize_post($post_content);

if (empty($post_content)) {
  $error = TCObject::ERR_NOT_SAVED;
}

$new_thread = null;

if (empty($error)) {
  $thread = new TCThread();
  $thread->board_id = $board_id;
  $thread->thread_title = $thread_title;
  $thread->first_post_id = 0;
  $thread->created_by_user = $user->user_id;
  $thread->updated_by_user = $user->user_id;
  $thread->created_time = time();
  $thread->updated_time = time();

  try {
    $new_thread = $db->save_object($thread);
  } catch (TCException $e) {
    $new_thread = null;
  }

  if (!empty($new_thread)) {
    $sanitizer = new TCPostSanitizer();

    // Create the thread's initial post.
    $post = new TCPost();
    $post->user_id = $user->user_id;
    $post->thread_id = $new_thread->thread_id;
    $post->content = $sanitizer->sanitize_post($post_content);
    $post->created_time = time();
    $post->updated_time = time();
    $post->updated_by_user = $user->user_id;

    $new_post = $db->save_object($post);

    if (!empty($new_post)) {
      // Assign first post ID for this thread.
      $new_thread->first_post_id = $new_post->post_id;
      $db->save_object($new_thread);
    } else {
      // Delete thread and exit with error if post cannot be created.
      $error = TCObject::ERR_NOT_SAVED;
      $db->delete_object($thread, $thread->thread_id);
    }
  }
}

if (!empty($ajax)) {
  header('Content-type: application/json; charset=utf-8');

  $response = new TCJSONResponse();

  $response->success = (empty($error));

  if (!empty($error)) {
    $error_message = new TCErrorMessage();
    $response->errors = $error_message->get_error_message('create-thread', $error);
  }

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to their new thread.
    $destination = TCURL::create_url($settings['page_thread'], [
      'thread' => $new_thread->thread_id,
    ]);
  } else {
    // Send user back to the new thread page with an error.
    $destination = TCURL::create_url($settings['page_new_thread'], [
      'board' => $board_id,
      'error' => $error,
    ]);
  }

  header('Location: '.$destination);
  exit;
}
