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

$threads = $db->load_objects(new TCThread(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($threads as $thread) {
  $data = array(
    'title' => $thread->thread_title,
    'object_id' => $thread->thread_id,
    'view_page_id' => $settings['page_thread'],
    'edit_page_id' => $settings['admin_page_edit_thread']
  );

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
