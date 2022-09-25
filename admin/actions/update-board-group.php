<?php

use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCObject;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can board group update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';

$board_group_id = filter_input(INPUT_POST, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$board_group_name = trim(filter_input(INPUT_POST, 'board_group_name', FILTER_SANITIZE_STRING));

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

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);

$error = null;

if (empty($board_group)) {
    $error = TCObject::ERR_NOT_FOUND;
}

$saved_board_group = null;

if (empty($error)) {
    $board_group->board_group_name = $board_group_name;
    $board_group->updated_time = time();

    $saved_board_group = $db->save_object($board_group);

    // Verify board group has been updated.
    if (empty($saved_board_group)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the board groups page.
$destination = '/admin/index.php?page='.$settings['admin_page_board_groups'].'&error='.$error;
header('Location: '.$destination);
exit;
