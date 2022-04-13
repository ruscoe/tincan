<?php
/**
 * Page template for admin board group editing.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCBoardGroup(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="board_group_name">Board Group Name</label>
  <input type="text" name="board_group_name" value="<?=$object->board_group_name?>" />
  <input type="hidden" name="object_type" value="board_group" />
  <input type="hidden" name="object_id" value="<?=$object->board_group_id?>" />
  <input type="submit" value="Update Board Group" />
</form>
