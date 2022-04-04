<?php
$page = $data['page'];

$board_group_id = filter_input(INPUT_GET, 'board_group', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);
?>

<form action="/actions/update-object.php" method="POST">
  <label for="board_group_name">Board Group Name</label>
  <input type="text" name="board_group_name" value="<?=$board_group->board_group_name?>" />
  <input type="hidden" name="object_type" value="board_group" />
  <input type="hidden" name="object_id" value="<?=$board_group->board_group_id?>" />
  <input type="submit" value="Update Board Group" />
</form>
