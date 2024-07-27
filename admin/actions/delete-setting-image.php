<?php

use TinCan\controllers\TCSettingController;
use TinCan\template\TCURL;

/**
 * Tin Can image setting deletion handler.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$setting = filter_input(INPUT_POST, 'setting', FILTER_SANITIZE_STRING);

$controller = new TCSettingController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->upload_setting_image($setting, $file);

if (empty($controller->get_error())) {
    // Send user to the settings page.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_forum_settings'),
        [
        'setting' => $setting
        ]
    );
} else {
    // Send user back to the settings page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_forum_settings'),
        [
        'setting' => $setting,
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
