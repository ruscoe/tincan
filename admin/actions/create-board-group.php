<?php

use TinCan\TCBoardGroup;
use TinCan\TCData;

// TODO: Check user role before anything else.

/**
 * Tin Can board group creation handler.
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

$db = new TCData();
$settings = $db->load_settings();

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
