<?php

use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCPage;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can page update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$page_id = filter_input(INPUT_POST, 'page_id', FILTER_SANITIZE_NUMBER_INT);
$page_title = trim(filter_input(INPUT_POST, 'page_title', FILTER_SANITIZE_STRING));
$template = trim(filter_input(INPUT_POST, 'template', FILTER_SANITIZE_STRING));

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

$page = $db->load_object(new TCPage(), $page_id);

$error = null;

if (empty($page)) {
    $error = TCObject::ERR_NOT_FOUND;
}

$saved_page = null;

if (empty($error)) {
    $page->page_title = $page_title;
    $page->template = $template;
    $page->updated_time = time();

    $saved_page = $db->save_object($page);

    // Verify page has been updated.
    if (empty($saved_page)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the pages page.
$destination = '/admin/index.php?page='.$settings['admin_page_pages'].'&error='.$error;
header('Location: '.$destination);
exit;
