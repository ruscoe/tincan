<?php
$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$pages = $db->load_objects(new TCPage(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($pages as $page) {
  $data = array(
    'title' => $page->page_title
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
