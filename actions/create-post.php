<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPagination;
use TinCan\TCPost;
use TinCan\TCPostSanitizer;
use TinCan\TCThread;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can create post handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-json-response.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-content.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to create a new post.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_POST)) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

// Check this post can be created in the given thread.
if (empty($error)) {
  $thread = $db->load_object(new TCThread(), $thread_id);

  // Validate thread.
  if (empty($thread)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

// Validate post content.
$post_sanitizer = new TCPostSanitizer();
$post_content = $post_sanitizer->sanitize_post($post_content);

if (empty($post_content)) {
  $error = TCObject::ERR_NOT_SAVED;
}

$sanitizer = new TCPostSanitizer();

$new_post = null;

if (empty($error)) {
  $post = new TCPost();
  $post->user_id = $user->user_id;
  $post->thread_id = $thread->thread_id;
  $post->content = $sanitizer->sanitize_post($post_content);
  $post->created_time = time();
  $post->updated_time = time();
  $post->updated_by_user = $user->user_id;

  $new_post = $db->save_object($post);

  if (empty($new_post)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (!empty($ajax)) {
  header('Content-type: application/json; charset=utf-8');

  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  try {
    $settings = $db->load_settings();
  } catch (TCException $e) {
    echo $e->getMessage();
    exit;
  }

  // Calculate the total pages in this thread so the user can be sent
  // directly to their new post.
  $conditions = [
      ['field' => 'thread_id', 'value' => $thread_id],
    ];

  $total_posts = $db->count_objects(new TCPost(), $conditions);
  $total_pages = TCPagination::calculate_total_pages($total_posts, $settings['posts_per_page']);

  $destination = '';

  if (empty($error)) {
    // Send user to their new post.
    $destination = TCURL::create_url($settings['page_thread'], [
      'thread' => $thread_id,
      'start_at' => $total_pages,
    ]);

    $destination .= '#post-'.$new_post->post_id;
  } else {
    // Send user back to the new post page with an error.
    // TODO: Add an anchor link to the form.
    $destination = TCURL::create_url($settings['page_thread'], [
      'thread' => $thread_id,
      'start_at' => $total_pages,
      'error' => $error,
    ]);
  }

  header('Location: '.$destination);
  exit;
}
