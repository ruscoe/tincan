<?php

use TinCan\controllers\TCSettingController;
use TinCan\template\TCURL;

/**
 * Tin Can image setting uploader.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$setting = filter_input(INPUT_POST, 'setting', FILTER_SANITIZE_STRING);

$file = $_FILES['image_file'];

$controller = new TCSettingController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->upload_setting_image($setting, $file);

if (empty($controller->get_error())) {
    // Send user to the image setting page.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_upload_setting_image'),
        [
        'setting' => $setting
        ]
    );
} else {
    // Send user back to the image setting page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_upload_setting_image'),
        [
        'setting' => $setting,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
