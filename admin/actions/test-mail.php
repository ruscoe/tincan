<?php

use TinCan\controllers\TCMailController;
use TinCan\template\TCURL;

/**
 * Tin Can email test handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_STRING);

$controller = new TCMailController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->send_test_mail($recipient);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the test mail page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_test_mail'));
} else {
    // Send user back to the test mail page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_test_mail'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
