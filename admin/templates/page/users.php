<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\db\TCData;
use TinCan\objects\TCRole;
use TinCan\objects\TCUser;

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

$indexed_roles = $db->get_indexed_objects(new TCRole(), 'role_id');

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$users = $db->load_objects(new TCUser(), [], $conditions, $order);
?>

<table class="objects">
  <th>Username</th>
  <th>Role</th>
  <th>Signup IP</th>
  <th>Last IP</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($users as $user) {
    $user_title = $user->username;
    $user_title .= ($user->suspended) ? ' [suspended]' : '';
    $data = [
      [
        'type' => 'text',
        'value' => $user_title,
      ],
      [
        'type' => 'text',
        'value' => $indexed_roles[$user->role_id]->role_name,
      ],
      [
        'type' => 'text',
        'value' => $user->signup_ip,
      ],
      [
        'type' => 'text',
        'value' => $user->last_ip,
      ],
      [
        'type' => 'link',
        'url' => '/index.php?page='.$settings['page_user'].'&user='.$user->user_id,
        'value' => 'View',
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_edit_user'].'&user_id='.$user->user_id,
        'value' => 'Edit',
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_delete_user'].'&user_id='.$user->user_id,
        'value' => 'Delete',
      ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
