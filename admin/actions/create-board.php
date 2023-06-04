<?php

use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can board creation handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';


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

$board = new TCBoard();

// Populate fields.
$db_fields = $board->get_db_fields();

foreach ($db_fields as $field) {
    if (isset($_POST[$field])) {
        $board->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
    }
}

$board->created_time = time();
$board->updated_time = time();

$saved_board = $db->save_object($board);

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_boards'];
header('Location: '.$destination);
exit;
