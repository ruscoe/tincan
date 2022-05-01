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

<div class="add-new">
  <a href="/admin/index.php?page=<?php echo $settings['admin_page_edit_thread']; ?>">New Thread</a>
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
    'title' => $thread->thread_title,
    'object_id' => $thread->thread_id,
    'view_url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id,
    'edit_url' => '/admin/index.php?page='.$settings['admin_page_edit_thread'].'&thread_id='.$thread->thread_id,
    'delete_url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=thread&object_id='.$thread->thread_id,
  ];

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
