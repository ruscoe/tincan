<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCThread;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can delete object handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$object_type = filter_input(INPUT_POST, 'object_type', FILTER_SANITIZE_STRING);
$object_id = filter_input(INPUT_POST, 'object_id', FILTER_SANITIZE_NUMBER_INT);
$delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING);

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

$class = null;
$object = null;
$page = null;

switch ($object_type) {
  case 'board_group':
    $class = new TCBoardGroup();
    $page = $settings['admin_page_board_groups'];
    break;
  case 'board':
    $class = new TCBoard();
    $page = $settings['admin_page_boards'];
    break;
  case 'page':
    $class = new TCPage();
    $page = $settings['admin_page_pages'];
    break;
  case 'thread':
    $class = new TCThread();
    $page = $settings['admin_page_threads'];
    break;
  case 'user':
    $class = new TCUser();
    $page = $settings['admin_page_users'];
    break;
}

if (!empty($class)) {
  $object = $db->load_object($class, $object_id);
}

$loaded_object = $db->load_object($object, $object_id);

if (!empty($loaded_object)) {
  $db->delete_object($loaded_object, $object_id);
}

$destination = '/admin/index.php?page='.$page;

header('Location: '.$destination);
exit;
