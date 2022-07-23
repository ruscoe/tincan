<?php

use TinCan\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPost;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can delete post handler.
 *
 * @since 0.04
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

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

$post = $db->load_object(new TCPost(), $post_id);

if (empty($post)) {
  $error = TCObject::ERR_NOT_FOUND;
}

if (empty($error) && isset($_POST['cancel'])) {
  // Cancel post deletion and return user to the thread.
  TCURL::create_url($settings['page_thread'], ['thread' => $post->thread_id]);

  header('Location: '.$destination);
  exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to delete this post.
if (empty($error) && (empty($user) || !$user->can_delete_post($post))) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

if (empty($error)) {
  $result = $db->delete_object($post, $post->post_id);

  if (!$result) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (!empty($ajax)) {
  header('Content-type: application/json; charset=utf-8');

  $response = new TCJSONResponse();
  $response->success = (empty($error));
  $response->post_id = (!empty($post)) ? $post->post_id : null;

  if (!empty($error)) {
    $error_message = new TCErrorMessage();
    $response->errors = $error_message->get_error_message('delete-post', $error);
  }

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the confirmation page.
    $destination = TCURL::create_url($settings['page_post_deleted'], [
      'thread' => $post->thread_id,
    ]);
  } else {
    // Send user back to the delete post page with an error.
    $destination = TCURL::create_url($settings['page_post_deleted'], [
      'post' => $post->post_id,
      'error' => $error,
    ]);
  }

  header('Location: '.$destination);
  exit;
}
