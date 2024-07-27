<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;

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

<?php

$db = new TCData();

// Filter results.
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

$conditions = [];

if (!empty($board_id)) {
    $conditions[] = [
      'field' => 'board_id',
      'value' => $board_id,
    ];
}

// TODO Sorting and pagination.
$order = [];

$threads = $db->load_objects(new TCThread(), [], $conditions, $order);
$indexed_boards = $db->get_indexed_objects(new TCBoard(), 'board_id');
?>

<form id="filters" action="/admin/actions/process-filters.php" method="POST">
  <div class="fieldset">
    <select name="board">
      <option value="">All boards</option>
      <?php
        foreach ($indexed_boards as $board) {
            $selected = ($board->board_id == $board_id) ? ' selected' : ''; ?>
        <option value="<?php echo $board->board_id; ?>"<?php echo $selected; ?>><?php echo $board->board_name; ?></option>
            <?php
        } ?>
    </select>
  </div>
  <div class="fieldset button">
    <input class="submit-bottom" type="submit" value="Filter threads" />
  </div>
  <input type="hidden" name="page" value="<?php echo $settings['admin_page_threads']; ?>" />
</form>

<table class="objects">
  <th>Thread Title</th>
  <th>Board</th>
  <th>Posts</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($threads as $thread) {
    $total_posts = $db->count_objects(new TCPost(), [['field' => 'thread_id', 'value' => $thread->thread_id]]);
    $data = [
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id,
      'value' => $thread->thread_title,
    ],
    [
      'type' => 'text',
      'value' => $indexed_boards[$thread->board_id]->board_name,
    ],
    [
      'type' => 'text',
      'value' => $total_posts,
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
