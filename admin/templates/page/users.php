<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCUser;

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

<table class="objects">
  <th>Username</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($users as $user) {
  $data = [
    'title' => $user->username,
    'object_id' => $user->user_id,
    'view_url' => '/index.php?page='.$settings['page_user'].'&user='.$user->user_id,
    'edit_page_id' => $settings['admin_page_edit_user'],
    'edit_url' => '/admin/index.php?page='.$settings['admin_page_edit_user'].'&object_id='.$user->user_id,
    'delete_url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=user&object_id='.$user->user_id,
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
