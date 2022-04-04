<?php

require '../../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require TC_BASE_PATH . '/actions/class-tc-json-response.php';

$object_type = filter_input(INPUT_POST, 'object_type', FILTER_SANITIZE_STRING);
$object_id = filter_input(INPUT_POST, 'object_id', FILTER_SANITIZE_NUMBER_INT);

$object = null;

switch ($object_type) {
  case 'board_group':
    $object = new TCBoardGroup();
    break;
  case 'board':
    $object = new TCBoard();
    break;
  case 'page':
    $object = new TCPage();
    break;
  case 'post':
    $object = new TCPost();
    break;
  case 'thread':
    $object = new TCThread();
    break;
  case 'user':
    $object = new TCUser();
    break;
}

$db = new TCData();

$response = new TCJSONResponse();

if (empty($object)) {
  $response->message = 'Unable to edit object at this time.';
  exit($response->get_output());
}

$loaded_object = $db->load_object($object, $object_id);

if (empty($loaded_object)) {
  $response->message = 'Unable to edit object at this time.';
  exit($response->get_output());
}

// Update object fields.
$db_fields = $loaded_object->get_db_fields();

foreach ($db_fields as $field) {
  if (isset($_POST[$field])) {
    $loaded_object->$field = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
  }
}

$loaded_object->updated_time = time();

$updated_object = $db->save_object($loaded_object);

$response->success = (!empty($updated_object));

exit($response->get_output());
