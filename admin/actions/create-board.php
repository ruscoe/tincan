<?php

use TinCan\TCBoard;
use TinCan\TCData;

// TODO: Check user role before anything else.

/**
 * Tin Can board creation handler.
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
