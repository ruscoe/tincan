<?php
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCBoard(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="board_name">Board Name</label>
  <input type="text" name="board_name" value="<?=$object->board_name?>" />
  <input type="hidden" name="object_type" value="board" />
  <input type="hidden" name="object_id" value="<?=$object->board_id?>" />
  <input type="submit" value="Update Board" />
</form>
