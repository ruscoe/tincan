<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;

/**
 * Page template for admin board group list.
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
  <a class="admin-button" href="/admin/index.php?page=<?php echo $settings['admin_page_edit_board_group']; ?>">New Board Group</a>
</div>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$board_groups = $db->load_objects(new TCBoardGroup(), [], $conditions, $order);
?>

<table class="objects">
  <th>Board Group Name</th>
  <th>Boards</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($board_groups as $board_group) {
    $total_boards = $db->count_objects(new TCBoard(), [['field' => 'board_group_id', 'value' => $board_group->board_group_id]]);
    $data = [
      [
        'type' => 'text',
        'value' => $board_group->board_group_name,
      ],
      [
        'type' => 'text',
        'value' => $total_boards,
      ],
      [
        'type' => 'link',
        'url' => '/index.php?page='.$settings['page_board_group'].'&board_group='.$board_group->board_group_id,
        'value' => 'View',
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_edit_board_group'].'&board_group_id='.$board_group->board_group_id,
        'value' => 'Edit',
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_delete_board_group'].'&board_group_id='.$board_group->board_group_id,
        'value' => 'Delete',
      ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
