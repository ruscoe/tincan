<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCPage;

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

<table class="objects">
  <th>Page Name</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($pages as $page) {
  $data = [
    'title' => $page->page_title,
    'object_id' => $page->page_id,
    'view_url' => '/index.php?page='.$page->page_id,
    'edit_url' => '/admin/index.php?page='.$settings['admin_page_edit_page'].'&object_id='.$page->page_id,
    'delete_url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=page&object_id='.$page->page_id,
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
