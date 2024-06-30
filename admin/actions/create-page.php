<?php

use TinCan\db\TCData;
use TinCan\objects\TCPage;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can page creation handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

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

$page = new TCPage();

// Populate fields.
$db_fields = $page->get_db_fields();

foreach ($db_fields as $field) {
    if (isset($_POST[$field])) {
        $page->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
    }
}

$page->created_time = time();
$page->updated_time = time();

// Custom pages are never required and can be deleted by an admin user.
$page->required = 0;

$saved_page = $db->save_object($page);

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_pages'];
header('Location: '.$destination);
exit;
