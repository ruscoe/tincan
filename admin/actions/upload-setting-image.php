<?php

use TinCan\db\TCData;
use TinCan\content\TCImage;
use TinCan\objects\TCObject;
use TinCan\objects\TCSetting;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can image setting uploader.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';


$setting = filter_input(INPUT_POST, 'setting', FILTER_SANITIZE_STRING);

$db = new TCData();
$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$settings['page_log_in']);
    exit;
}

// Check the given setting is an image type setting.
$setting_objects = $db->get_indexed_objects(new TCSetting(), 'setting_name');
$image_setting = $setting_objects[$setting];

if (empty($image_setting) || ($image_setting->type !== 'image')) {
    $error = TCImage::ERR_FILE_GENERAL;
}

$file = $_FILES['image_file'];

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

// The image filename is identical to the setting name.
// TODO: Eventually integrate this into a media management system.
$target_file = $setting.'.jpg';
$target_full_path = TC_UPLOADS_PATH.'/'.$target_file;

if (empty($error) && !move_uploaded_file($file['tmp_name'], $target_full_path)) {
    $error = TCImage::ERR_FILE_GENERAL;
}

// Update setting value with new filename.
$image_setting->value = '/uploads/'.$target_file;
$db->save_object($image_setting);

// Return to the upload image page.
$destination = '/admin/index.php?page='.$settings['admin_page_upload_setting_image'].'&setting='.$setting.'&error='.$error;
header('Location: '.$destination);
exit;
