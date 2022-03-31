<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-user.php';
require TC_BASE_PATH . '/admin/class-tc-admin-template.php';

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = null;

$db = new TCData();

// Get page template if available, otherwise default to dashboard.
if (!empty($page_id)) {
  $page = $db->load_object(new TCPage(), $page_id);

  $page_template = (!empty($page)) ? $page->template : '404';
}
else {
  $page_template = 'dashboard';
}

$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_object(new TCUser(), $user_id) : null;

TCAdminTemplate::render('header', array('settings' => $settings, 'user' => $user));

TCAdminTemplate::render('page/' . $page_template, array('page' => $page));

TCAdminTemplate::render('footer', null);
