<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Admin page object filter handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$controller = new TCUserController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

// Sanitize parameters from the filter form and create admin page URL.
$page_id = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);

$page_params = array_keys($_POST);

$sanitized_params = [];

foreach ($page_params as $param) {
    if ('page' == $param) {
        continue;
    }

    $sanitized_params[$param] = filter_input(INPUT_POST, $param, FILTER_SANITIZE_STRING);
}

$destination = TCURL::create_admin_url($page_id, $sanitized_params);

header('Location: '.$destination);
exit;
