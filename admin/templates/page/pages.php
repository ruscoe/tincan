<?php
/**
 * Page template for admin page list.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$pages = $db->load_objects(new TCPage(), [], $conditions, $order);
?>

<table>
<?php
foreach ($pages as $page) {
  $data = [
    'title' => $page->page_title,
    'object_id' => $page->page_id,
    'view_page_id' => '',
    'edit_page_id' => $settings['admin_page_edit_page'],
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
