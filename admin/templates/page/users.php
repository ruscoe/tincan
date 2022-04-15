<?php
/**
 * Page template for admin user list.
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

$users = $db->load_objects(new TCUser(), [], $conditions, $order);
?>

<table>
<?php
foreach ($users as $user) {
  $data = [
    'title' => $user->username,
    'object_id' => $user->user_id,
    'view_page_id' => $settings['page_user'],
    'edit_page_id' => $settings['admin_page_edit_user'],
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
