<?php

use TinCan\TCData;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPost;
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

require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-content.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require 'class-tc-json-response.php';

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

$settings = $db->load_settings();

$post = $db->load_object(new TCPost(), $post_id);

if (isset($_POST['cancel'])) {
  // Cancel post deletion and return user to the thread.
  $destination = '/?page='.$settings['page_thread'].'&thread='.$post->thread_id;

  header('Location: '.$destination);
  exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to delete this post.
if (empty($user) || !$user->can_delete_post($post)) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

if (empty($error)) {
  $result = $db->delete_object($post, $post->post_id);

  if (!$result) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the confirmation page.
    $destination = '/?page='.$settings['page_post_deleted'].'&thread='.$post->thread_id;
  } else {
    // Send user back to the delete post page with an error.
    $destination .= '/?page='.$settings['page_delete_post'].'&post='.$post->post_id.'&error='.$error;
  }

  header('Location: '.$destination);
  exit;
}
