<?php

use TinCan\controllers\TCSettingController;
use TinCan\template\TCURL;

/**
 * Tin Can save settings handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$submitted_fields = $_POST;

$controller = new TCSettingController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->update_settings($submitted_fields);

if (empty($controller->get_error())) {
    // Send user to the settings page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_forum_settings'));
} else {
    // Send user back to the settings page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_forum_settings'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
