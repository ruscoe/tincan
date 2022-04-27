<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCPost;
use TinCan\TCThread;
use TinCan\TCUser;

/**
 * Page template for admin board editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$object_type = filter_input(INPUT_GET, 'object_type', FILTER_SANITIZE_STRING);
$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
$delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING);

$db = new TCData();
$settings = $db->load_settings();

$class = null;
$object = null;
$page = null;

switch ($object_type) {
  case 'board_group':
    $class = new TCBoardGroup();
    $page = $settings['admin_page_edit_board_group'];
    break;
  case 'board':
    $class = new TCBoard();
    $page = $settings['admin_page_edit_board'];
    break;
  case 'page':
    $class = new TCPage();
    $page = $settings['admin_page_edit_page'];
    break;
  case 'thread':
    $class = new TCThread();
    $page = $settings['admin_page_edit_thread'];
    break;
  case 'user':
    $class = new TCUser();
    $page = $settings['admin_page_edit_user'];
    break;
}

if (!empty($class)) {
  $object = $db->load_object($class, $object_id);
}
?>

<h1>Really delete <?php echo $object->get_name(); ?>?</h1>

<form action="/admin/actions/delete-object.php" method="POST">
  <input type="hidden" name="object_type" value="<?php echo $object_type; ?>" />
  <input type="hidden" name="object_id" value="<?php echo $object_id; ?>" />
  <input type="submit" value="Delete" />
</form>
