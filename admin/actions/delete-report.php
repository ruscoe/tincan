<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can report deletion handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$report_id = filter_input(INPUT_POST, 'report_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCPostController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->delete_report($report_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the reports page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_reports'));
} else {
    // Send user back to the delete report page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_delete_report'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
