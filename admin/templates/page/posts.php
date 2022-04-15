<?php
/**
 * Page template for admin post list.
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

$posts = $db->load_objects(new TCPost(), [], $conditions, $order);
?>

<table>
<?php
foreach ($posts as $post) {
  $data = [
    'title' => $post->post_id,
    'object_id' => $post->post_id,
    'view_page_id' => '',
    'edit_page_id' => $settings['admin_page_edit_post'],
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
