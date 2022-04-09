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

$pages = $db->load_objects(new TCPage(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($pages as $page) {
    $data = array(
    'title' => $page->page_title,
    'object_id' => $page->page_id,
    'view_page_id' => '',
    'edit_page_id' => $settings['admin_page_edit_page']
  );
    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
