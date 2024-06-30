<?php

use TinCan\db\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\content\TCImage;
use TinCan\TCJSONResponse;
use TinCan\objects\TCObject;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can update post handler.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';


$avatar_user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to upload an avatar.
if (empty($user) && $user->can_edit_user($avatar_user_id)) {
    $error = TCUser::ERR_NOT_AUTHORIZED;
}

$avatar_user = $db->load_user($avatar_user_id);

$file = $_FILES['avatar_image'];

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

$target_path = 'avatars/'.substr($avatar_user->user_id, -1);

if (!is_dir(getenv('TC_UPLOADS_PATH').'/'.$target_path)) {
    // If the avatar upload path doesn't exist, create it with write permissions.
    mkdir(getenv('TC_UPLOADS_PATH').'/'.$target_path, 0644, true);
}

$target_file = $avatar_user->user_id.'.jpg';
$target_full_path = getenv('TC_UPLOADS_PATH').'/'.$target_path.'/'.$target_file;

if (empty($error) && !move_uploaded_file($file['tmp_name'], $target_full_path)) {
    $error = TCImage::ERR_FILE_GENERAL;
}

// TODO: Resize and crop file to a square.
$scaled_image = $image->scale_to_square($target_full_path, 256);

if (empty($error) && !imagejpeg($scaled_image, $target_full_path)) {
    $error = TCImage::ERR_FILE_GENERAL;
}

if (empty($error)) {
    $avatar_user->avatar = '/uploads/'.$target_path.'/'.$target_file;
    $avatar_user->updated_time = time();

    if (!$db->save_object($avatar_user)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

if (!empty($ajax)) {
    header('Content-type: application/json; charset=utf-8');

    $response = new TCJSONResponse();

    $response->success = (empty($error));

    if (!empty($error)) {
        $error_message = new TCErrorMessage();
        $response->errors = $error_message->get_error_message('upload-avatar', $error);
    }

    exit($response->get_output());
} else {
    try {
        $settings = $db->load_settings();
    } catch (TCException $e) {
        echo $e->getMessage();
        exit;
    }

    $destination = null;

    if (empty($error)) {
        // Send user to the updated page.
        $url_id = ($settings['enable_urls']) ? $settings['base_url_users'] : $settings['page_user'];
        $destination = TCURL::create_url($url_id, ['user' => $avatar_user->user_id], $settings['enable_urls'], $avatar_user->get_slug());
    } else {
        // Send user back to the page with an error.
        $url_id = ($settings['enable_urls']) ? $settings['base_url_users'] : $settings['page_user'];
        $destination = TCURL::create_url($url_id, ['user' => $avatar_user->user_id, 'error' => $error], $settings['enable_urls'], $avatar_user->get_slug());
    }

    header('Location: '.$destination);
    exit;
}
