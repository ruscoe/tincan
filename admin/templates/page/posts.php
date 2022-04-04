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

$posts = $db->load_objects(new TCPost(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($posts as $post) {
  $data = array(
    'title' => $post->post_id,
    'object_id' => $post->post_id,
    'view_page_id' => '',
    'edit_page_id' => $settings['admin_page_edit_post']
  );
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
