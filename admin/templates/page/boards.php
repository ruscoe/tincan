<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCBoard;
use TinCan\TCData;

/**
 * Page template for admin board list.
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
  <a href="/admin/index.php?page=<?php echo $settings['admin_page_edit_board']; ?>">New Board</a>
</div>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$boards = $db->load_objects(new TCBoard(), [], $conditions, $order);
?>

<table class="objects">
  <th>Board Name</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($boards as $board) {
  $data = [
    'title' => $board->board_name,
    'object_id' => $board->board_id,
    'view_url' => '/index.php?page='.$settings['page_board'].'&board='.$board->board_id,
    'edit_url' => '/admin/index.php?page='.$settings['admin_page_edit_board'].'&board_id='.$board->board_id,
    'delete_url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=board&object_id='.$board->board_id,
  ];

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
