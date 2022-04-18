<?php
/**
 * Tin Can update post handler.
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
$post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

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

  $updated_post = $db->save_object($post);

  if (empty($updated_post)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $settings = $db->load_settings();

  // TODO: Get the page of the thread this post appears on.
  $page = 1;

  $destination = '';

  if (empty($error)) {
    // Send user to their updated post.
    $destination = '/?page='.$settings['page_thread'].'&thread='.$post->thread_id
    .'&start_at='.$page.'#post-'.$post->post_id;
  }
  else
  {
    // Send user back to the new post page with an error.
    $destination .= '/?page='.$settings['page_edit_post'].'&post='.$post->post_id.'&error='.$error;
  }

  header('Location: '.$destination);
  exit;
}
