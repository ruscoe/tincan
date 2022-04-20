<?php

use TinCan\TCData;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPost;
use TinCan\TCPostSanitizer;
use TinCan\TCUser;
use TinCan\TCUserSession;

$file = $_FILES['avatar_image'];
var_dump($file);

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

$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// TODO: Check user has permission to upload an avatar.
if (empty($user)) {
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

if (empty($error) && (empty($file) || $file['error'] !== UPLOAD_ERR_OK)) {
  // TODO: File-specific error message.
  $error = TCUser::ERR_NOT_AUTHORIZED;
}

// TODO: Check file size.
// TODO: Check file MIME type.
// TODO: Resize file.
// TODO: Convert file to jpg.
// TODO: Move file to final destination.

$settings = $db->load_settings();

// Avatar filename is the user's ID followed by the file extension.
// The directory containing the avatar file is named for the last digit of
// the user's ID. This just allows us to split up files and avoid massive
// directories.
$target_file = substr($user->user_id, -1) . '/' . $user->user_id . '.jpg';

if (!move_uploaded_file($file['tmp_name'], TC_UPLOADS_PATH . '/avatars/' . $target_file)) {
  // TODO: File save error.
}

if (empty($error)) {
  $user->avatar = $target_file;

  if (!$db->save_object($user)) {
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
    // Send user to their updated page.
    $destination = '/?page='.$settings['page_user'].'&user='.$user->user_id;
  }
  else
  {
    // Send user back to their page with an error.
    $destination .= '/?page='.$settings['page_user'].'&user='.$user->user_id.'&error='.$error;
  }

  header('Location: '.$destination);
  exit;
}