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

// Check the given setting exists and is an image type setting.
$setting_objects = $db->get_indexed_objects(new TCSetting(), 'setting_name');
$image_setting = $setting_objects[$setting];

if (empty($image_setting) || ($image_setting->type !== 'image')) {
    throw new TCException('Unable to find image setting '.$setting);
}

// Image settings are given an empty filename rather than being deleted.
// This just avoids complications uploading a new image later.
$image_setting->value = '';
$db->save_object($image_setting);

// Return to forum settings page.
$destination = '/admin/index.php?page='.$settings['admin_page_forum_settings'];

header('Location: '.$destination);
exit;
