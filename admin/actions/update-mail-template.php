<?php

use TinCan\controllers\TCMailController;
use TinCan\template\TCURL;

/**
 * Tin Can mail template update handler.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$mail_template_id = filter_input(INPUT_POST, 'mail_template_id', FILTER_SANITIZE_NUMBER_INT);
$mail_template_name = filter_input(INPUT_POST, 'mail_template_name', FILTER_SANITIZE_STRING);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

$controller = new TCMailController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->edit_mail_template($mail_template_id, $mail_template_name, $content);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the mail templates page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_mail_templates'));
} else {
    // Send user back to the edit mail template page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_edit_mail_template'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
