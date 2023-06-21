<?php

use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can board group creation handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/vendor/autoload.php';

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

$board_group = new TCBoardGroup();

// Populate fields.
$db_fields = $board_group->get_db_fields();

foreach ($db_fields as $field) {
    if (isset($_POST[$field])) {
        $board_group->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
    }
}

$board_group->created_time = time();
$board_group->updated_time = time();

$saved_board_group = $db->save_object($board_group);

// Return to the threads page.
$destination = '/admin/index.php?page='.$settings['admin_page_board_groups'];
header('Location: '.$destination);
exit;
