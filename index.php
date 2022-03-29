<?php

require 'tc-config.php';

require 'includes/include-db.php';
require 'includes/include-objects.php';
require 'includes/include-template.php';

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

// Get page template if available, otherwise default to front page.
if (!empty($page_id)) {
  $db = new TCData();
  $page = $db->load_object(new TCPage(), $page_id);

  $page_template = (!empty($page)) ? $page->template : '404';
}
else {
  $page_template = 'front';
}

TCTemplate::render('header', NULL);

TCTemplate::render('page/' . $page_template, array('page' => $page));

TCTemplate::render('footer', NULL);
