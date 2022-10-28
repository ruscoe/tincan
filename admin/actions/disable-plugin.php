<?php

use TinCan\TCData;
use TinCan\TCPlugin;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Admin page plugin disabler.
 *
 * @since 0.14
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

$plugin_id = filter_input(INPUT_GET, 'plugin_id', FILTER_SANITIZE_NUMBER_INT);

$db->delete_object(new TCPlugin(), $plugin_id);

$destination = '/admin/index.php?page='.$settings['admin_page_plugins'];

header('Location: '.$destination);
exit;
