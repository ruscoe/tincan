<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Admin section entry point.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';
require TC_BASE_PATH.'/admin/class-tc-admin-template.php';

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = null;

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

// Get page template if available, otherwise default to forum settings.
if (!empty($page_id)) {
  $page = $db->load_object(new TCPage(), $page_id);

  $page_template = (!empty($page)) ? $page->template : '404';
} else {
  header('Location: /admin/index.php?page='.$settings['admin_page_forum_settings']);
  exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_object(new TCUser(), $user_id) : null;

TCAdminTemplate::render('header', ['settings' => $settings, 'user' => $user]);

TCAdminTemplate::render('page/'.$page_template, ['page' => $page, 'settings' => $settings]);
