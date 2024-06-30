<?php

use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can board group deletion handler.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_group_id = filter_input(INPUT_POST, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$board_fate = filter_input(INPUT_POST, 'board_fate', FILTER_SANITIZE_STRING);
$move_to_board_group_id = filter_input(INPUT_POST, 'move_to_board_group_id', FILTER_SANITIZE_NUMBER_INT);

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

if (empty($board_group)) {
    throw new TCException('Unable to find board group ID '.$board_group_id);
}

$db->delete_object(new TCBoardGroup(), $board_group->board_group_id);

$boards = $db->load_objects(new TCBoard(), null, [['field' => 'board_group_id', 'value' => $board_group->board_group_id]]);

if ('move' == $board_fate) {
    foreach ($boards as $board) {
        $board->board_group_id = $move_to_board_group_id;
        $db->save_object($board);
    }
} else {
    foreach ($boards as $board) {
        $db->delete_object(new TCBoard(), $board->board_id);
    }
}

$destination = '/admin/index.php?page='.$settings['admin_page_board_groups'];

header('Location: '.$destination);
exit;
