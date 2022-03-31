<?php

require 'tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

$db = new TCData();

$pages = array();
$users = array();
$board_groups = array();
$boards = array();
$threads = array();
$posts = array();

$settings = array(
  (object) array(
    'setting_name' => 'date_format',
    'type' => 'text',
    'title' => 'Date format',
    'value' => 'Y-m-d H:i:s',
    'required' => 1,
  )
);

foreach ($settings as $setting) {
  $db->save_object(new TCSetting($setting));
}
