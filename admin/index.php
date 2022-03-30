<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';

require TC_BASE_PATH . '/admin/class-tc-admin-template.php';

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = NULL;

$db = new TCData();
var_dump($page_id);
// Get page template if available, otherwise default to dashboard.
if (!empty($page_id)) {
  $page = $db->load_object(new TCPage(), $page_id);

  $page_template = (!empty($page)) ? $page->template : '404';
}
else {
  $page_template = 'dashboard';
}

$settings = $db->load_settings();

TCAdminTemplate::render('header', array('settings' => $settings));

TCAdminTemplate::render('page/' . $page_template, array('page' => $page));

TCAdminTemplate::render('footer', NULL);
