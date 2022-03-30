<?php
$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$users = $db->load_objects(new TCUser(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($users as $user) {
  $data = array(
    'title' => $user->username
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
