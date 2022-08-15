<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCThread;

/**
 * Page template for admin thread list.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?php echo $page->page_title; ?></h1>

<div class="objects-nav">
  <a class="admin-button" href="/admin/index.php?page=<?php echo $settings['admin_page_edit_thread']; ?>">New Thread</a>
</div>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$threads = $db->load_objects(new TCThread(), [], $conditions, $order);
?>

<table class="objects">
  <th>Thread Title</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($threads as $thread) {
  $data = [
    [
      'type' => 'text',
      'value' => $thread->thread_title,
    ],
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id,
      'value' => 'View',
    ],
    [
      'type' => 'link',
      'url' => '/admin/index.php?page='.$settings['admin_page_edit_thread'].'&thread_id='.$thread->thread_id,
      'value' => 'Edit',
    ],
    [
      'type' => 'link',
      'url' => '/admin/index.php?page='.$settings['admin_page_delete_thread'].'&thread_id='.$thread->thread_id,
      'value' => 'Delete',
    ],
  ];

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
