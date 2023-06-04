<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\objects\TCPage;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

// Tin Can Forum version.
define('TC_VERSION', '0.15');

/**
 * Admin section entry point.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../vendor/autoload.php';
require '../tc-config.php';

require TC_BASE_PATH.'/admin/class-tc-admin-template.php';

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = null;

$db = new TCData();

try {
    $settings = $db->load_settings();
} catch (TCException $e) {
    echo $e->getMessage();
    exit;
}

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

// Get page template if available, otherwise default to forum settings.
if (!empty($page_id)) {
    $page = $db->load_object(new TCPage(), $page_id);

    $page_template = (!empty($page)) ? $page->template : '404';
} else {
    header('Location: /admin/index.php?page='.$settings['admin_page_forum_settings']);
    exit;
}

TCAdminTemplate::render('header', ['settings' => $settings, 'user' => $user]);

TCAdminTemplate::render('page/'.$page_template, ['page' => $page, 'settings' => $settings, 'user' => $user]);
