<?php

use TinCan\TCData;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Admin page object filter handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';
require TC_BASE_PATH.'/core/template/class-tc-url.php';

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

$destination = '/admin'.TCURL::create_url($page_id, $sanitized_params);

header('Location: '.$destination);
exit;
