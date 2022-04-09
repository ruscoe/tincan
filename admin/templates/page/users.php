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

$users = $db->load_objects(new TCUser(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($users as $user) {
    $data = array(
    'title' => $user->username,
    'object_id' => $user->user_id,
    'view_page_id' => $settings['page_user'],
    'edit_page_id' => $settings['admin_page_edit_user']
  );
    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
