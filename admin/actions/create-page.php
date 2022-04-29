<?php

use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCUser;

// TODO: Check user role before anything else.

/**
 * Tin Can page creation handler.
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

$page = new TCPage();

// Populate fields.
$db_fields = $page->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $page->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

$page->created_time = time();
$page->updated_time = time();

$saved_page = $db->save_object($page);

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_pages'];
header('Location: '.$destination);
exit;
