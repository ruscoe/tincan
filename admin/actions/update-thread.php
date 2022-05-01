<?php

use TinCan\TCData;
use TinCan\TCObject;
use TinCan\TCThread;

// TODO: Check user role before anything else.

/**
 * Tin Can thread update handler.
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

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = trim(filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING));
$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();
$settings = $db->load_settings();

$thread = $db->load_object(new TCThread(), $thread_id);

$error = null;

if (empty($thread)) {
  $error = TCObject::ERR_NOT_FOUND;
}

$saved_thread = null;

if (empty($error)) {
  $thread->thread_title = $thread_title;
  $thread->board_id = $board_id;
  $thread->updated_time = time();

  $saved_thread = $db->save_object($thread);

  // Verify thread has been updated.
  if (empty($saved_thread)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_threads'].'&error='.$error;
header('Location: '.$destination);
exit;
