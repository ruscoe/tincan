<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCPost;
use TinCan\TCThread;
use TinCan\TCUser;

/**
 * Tin Can update object handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require TC_BASE_PATH.'/actions/class-tc-json-response.php';

$object_type = filter_input(INPUT_POST, 'object_type', FILTER_SANITIZE_STRING);
$object_id = filter_input(INPUT_POST, 'object_id', FILTER_SANITIZE_NUMBER_INT);
$saved = filter_input(INPUT_POST, 'saved', FILTER_SANITIZE_STRING);

$db = new TCData();
$settings = $db->load_settings();

$object = null;
$page = null;

switch ($object_type) {
  case 'board_group':
    $object = new TCBoardGroup();
    $page = $settings['admin_page_board_groups'];
    break;
  case 'board':
    $object = new TCBoard();
    $page = $settings['admin_page_boards'];
    break;
  case 'page':
    $object = new TCPage();
    $page = $settings['admin_page_pages'];
    break;
  case 'thread':
    $object = new TCThread();
    $page = $settings['admin_page_threads'];
    break;
  case 'user':
    $object = new TCUser();
    $page = $settings['admin_page_users'];
    break;
}

$error = false;
$saved = false;

if (empty($object)) {
  $error = true;
}

// TODO: Error handling here.
$loaded_object = $db->load_object($object, $object_id);

// Update object fields.
$db_fields = $loaded_object->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $loaded_object->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

if (empty($object_id)) {
  $loaded_object->created_time = time();
}

$loaded_object->updated_time = time();

$updated_object = $db->save_object($loaded_object);

$destination = '/admin/index.php?page='.$page.'&object_id='.$updated_object->get_primary_key_value().'&saved='.(!$error);

header('Location: '.$destination);
exit;
