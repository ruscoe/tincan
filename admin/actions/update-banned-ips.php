<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can banned IP addresses update handler.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$banned_ips = trim(filter_input(INPUT_POST, 'banned_ips', FILTER_SANITIZE_STRING));

$controller = new TCUserController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->update_banned_ips($banned_ips);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the banned IPs page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_banned_ips'));
} else {
    // Send user back to the banned IPs page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_banned_ips'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
