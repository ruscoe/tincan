<?php
$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$threads = $db->load_objects(new TCThread(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($threads as $thread) {
  $data = array(
    'title' => $thread->thread_title
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
