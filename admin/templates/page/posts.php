<?php
$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$posts = $db->load_objects(new TCPost(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($posts as $post) {
  $data = array(
    'title' => $post->post_id
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
