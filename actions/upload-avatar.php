<?php

use TinCan\TCData;
use TinCan\TCImage;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCUser;
use TinCan\TCUserSession;

$file = $_FILES['avatar_image'];

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

if (empty($error) && (empty($file) || UPLOAD_ERR_OK !== $file['error'])) {
  $error = TCImage::ERR_FILE_GENERAL;
}

$image_data = getimagesize($file['tmp_name']);

$image = new TCImage();
$image->width = $image_data[0];
$image->height = $image_data[1];
$image->file_type = $image_data[2];
$image->mime_type = $image_data['mime'];
$image->file_size = $file['size'];

// Check for valid file type.
if (empty($error) && !$image->is_valid_type()) {
  $error = TCImage::ERR_FILE_TYPE;
}

// Check file size.
if (empty($error) && !$image->is_valid_size()) {
  $error = TCImage::ERR_FILE_SIZE;
}

// Avatar filename is the user's ID followed by the file extension.
// The directory containing the avatar file is named for the last digit of
// the user's ID. This just allows us to split up files and avoid massive
// directories.
$target_file = substr($user->user_id, -1).'/'.$user->user_id.'.jpg';
$target_full_path = TC_UPLOADS_PATH.'/avatars/'.$target_file;

if (empty($error) && !move_uploaded_file($file['tmp_name'], $target_full_path)) {
  $error = TCImage::ERR_FILE_GENERAL;
}

// TODO: Resize and crop file to a square.
$scaled_image = $image->scale_to_square($target_full_path, 256);

if (empty($error) && !imagejpeg($scaled_image, $target_full_path)) {
  $error = TCImage::ERR_FILE_GENERAL;
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
  $settings = $db->load_settings();

  $destination = '';

  if (empty($error)) {
    // Send user to their updated page.
    $destination = '/?page='.$settings['page_user'].'&user='.$user->user_id;
  } else {
    // Send user back to their page with an error.
    $destination .= '/?page='.$settings['page_user'].'&user='.$user->user_id.'&error='.$error;
  }

  header('Location: '.$destination);
  exit;
}
