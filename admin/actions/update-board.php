<?php

use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can board update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/vendor/autoload.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$board_group_id = filter_input(INPUT_POST, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$board_name = trim(filter_input(INPUT_POST, 'board_name', FILTER_SANITIZE_STRING));

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

$board = $db->load_object(new TCBoard(), $board_id);

$error = null;

if (empty($board)) {
    $error = TCObject::ERR_NOT_FOUND;
}

$saved_board = null;

if (empty($error)) {
    $board->board_name = $board_name;
    $board->board_group_id = $board_group_id;
    $board->updated_time = time();

    $saved_board = $db->save_object($board);

    // Verify board has been updated.
    if (empty($saved_board)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_boards'].'&error='.$error;
header('Location: '.$destination);
exit;
