<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCThread;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can board deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_fate = filter_input(INPUT_POST, 'thread_fate', FILTER_SANITIZE_STRING);
$move_to_board_id = filter_input(INPUT_POST, 'move_to_board_id', FILTER_SANITIZE_NUMBER_INT);

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

if (empty($board)) {
  throw new TCException('Unable to find board ID '.$board_id);
}

$db->delete_object(new TCBoard(), $board->board_id);

$threads = $db->load_objects(new TCThread(), null, [['field' => 'board_id', 'value' => $board->board_id]]);

if ($thread_fate == 'move') {
  foreach ($threads as $thread) {
    $thread->board_id = $board->board_id;
    $db->save_object($thread);
  }
}
else {
  foreach ($threads as $thread) {
    $db->delete_object(new TCThread(), $thread->thread_id);
  }
}

$destination = '/admin/index.php?page='.$settings['admin_page_boards'];

header('Location: '.$destination);
exit;
