<?php

use TinCan\TCData;
use TinCan\TCRole;
use TinCan\TCUser;

// TODO: Check user role before anything else.

/**
 * Tin Can user creation handler.
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

$user = new TCUser();

// Populate fields.
$db_fields = $user->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $user->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

$user->created_time = time();
$user->updated_time = time();

$saved_user = $db->save_object($user);

// Return to the users page.
$destination = '/admin/index.php?page='.$settings['admin_page_users'];
header('Location: '.$destination);
exit;
