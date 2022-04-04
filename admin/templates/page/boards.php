<?php
$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$boards = $db->load_objects(new TCBoard(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($boards as $board) {
  $data = array(
    'title' => $board->board_name
  );

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
