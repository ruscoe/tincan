<?php

use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can user deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$delete_user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

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

$delete_user = $db->load_object(new TCUser(), $delete_user_id);

if (empty($delete_user)) {
    throw new TCException('Unable to find user ID '.$delete_user_id);
}

if ($delete_user->user_id == $user->user_id) {
    throw new TCException('User cannot delete their own account.');
}

$db->delete_object(new TCUser(), $delete_user->user_id);

$destination = '/admin/index.php?page='.$settings['admin_page_users'];

header('Location: '.$destination);
exit;
