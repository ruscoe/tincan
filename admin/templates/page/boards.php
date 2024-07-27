<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;
use TinCan\objects\TCThread;

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

<div class="objects-nav">
  <a class="admin-button" href="/admin/index.php?page=<?php echo $settings['admin_page_edit_board']; ?>">New Board</a>
</div>

<?php

// Filter results.
$board_group_id = filter_input(INPUT_GET, 'board_group', FILTER_SANITIZE_NUMBER_INT);

$conditions = [];

if (!empty($board_group_id)) {
    $conditions[] = [
      'field' => 'board_group_id',
      'value' => $board_group_id,
    ];
}

// TODO Sorting and pagination.
$order = [];

$db = new TCData();

$boards = $db->load_objects(new TCBoard(), [], $conditions, $order);
$indexed_board_groups = $db->get_indexed_objects(new TCBoardGroup(), 'board_group_id');
?>

<form id="filters" action="/admin/actions/process-filters.php" method="POST">
  <div class="fieldset">
    <select name="board_group">
      <option value="">All board groups</option>
      <?php
        foreach ($indexed_board_groups as $board_group) {
            $selected = ($board_group->board_group_id == $board_group_id) ? ' selected' : ''; ?>
        <option value="<?php echo $board_group->board_group_id; ?>"<?php echo $selected; ?>><?php echo $board_group->board_group_name; ?></option>
            <?php
        } ?>
    </select>
  </div>
  <div class="fieldset button">
    <input class="submit-bottom" type="submit" value="Filter boards" />
  </div>
  <input type="hidden" name="page" value="<?php echo $settings['admin_page_boards']; ?>" />
</form>

<table class="objects">
  <th>Board Name</th>
  <th>Board Group</th>
  <th>Threads</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($boards as $board) {
    $total_threads = $db->count_objects(new TCThread(), [['field' => 'board_id', 'value' => $board->board_id]]);
    $data = [
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_board'].'&board='.$board->board_id,
      'value' => $board->board_name,
    ],
    [
      'type' => 'text',
      'value' => $indexed_board_groups[$board->board_group_id]->board_group_name,
    ],
    [
      'type' => 'text',
      'value' => $total_threads,
    ],
    [
      'type' => 'link',
      'url' => '/admin/index.php?page='.$settings['admin_page_edit_board'].'&board_id='.$board->board_id,
      'value' => 'Edit',
    ],
    [
      'type' => 'link',
      'url' => '/admin/index.php?page='.$settings['admin_page_delete_board'].'&board_id='.$board->board_id,
      'value' => 'Delete',
    ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
