<?php
$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$board_groups = $db->load_objects(new TCBoardGroup(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($board_groups as $board_group) {
  $data = array(
    'title' => $board_group->board_group_name
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
