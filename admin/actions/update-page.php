<?php

use TinCan\TCData;
use TinCan\TCObject;
use TinCan\TCPage;

// TODO: Check user role before anything else.

/**
 * Tin Can page update handler.
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

$page_id = filter_input(INPUT_POST, 'page_id', FILTER_SANITIZE_NUMBER_INT);
$page_title = trim(filter_input(INPUT_POST, 'page_title', FILTER_SANITIZE_STRING));
$template = trim(filter_input(INPUT_POST, 'template', FILTER_SANITIZE_STRING));

$db = new TCData();
$settings = $db->load_settings();

$page = $db->load_object(new TCPage(), $page_id);

$error = null;

if (empty($page)) {
  $error = TCObject::ERR_NOT_FOUND;
}

$saved_page = null;

if (empty($error)) {
  $page->page_title = $page_title;
  $page->template = $template;
  $page->updated_time = time();

  $saved_page = $db->save_object($page);

  // Verify page has been updated.
  if (empty($saved_page)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

// Return to the pages page.
$destination = '/admin/index.php?page='.$settings['admin_page_pages'].'&error='.$error;
header('Location: '.$destination);
exit;
