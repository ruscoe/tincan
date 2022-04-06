<?php
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

$board = $db->load_object(new TCBoard(), $board_id);
?>

<h1><?=$board->board_name?></h1>

<div id="board-navigation">
  <ul>
    <li><a href="/?page=<?=$settings['page_new_thread']?>&board=<?=$board->board_id?>">New thread</a></li>
  </ul>
</div>

<?php

$settings = $db->load_settings();

// Get threads in this board; order by thread with most recent post.
$conditions = array(
  array(
    'field' => 'board_id',
    'value' => $board->board_id
  )
);

$order = array(
  'field' => 'updated_time',
  'direction' => 'DESC'
);

// TODO: Set bounds for offset so nothing crazy happens.
//   Calculate maximum page number from posts.
//   Avoid negative numbers.
// $start_at is the page number. Records start at 0, so page 1 is technically 0.
// Subtract 1 from $start_at
$offset = ($start_at > 1) ? ($start_at - 1) : 0;
// TODO: Some code duplication in thread.php. Solve this somehow.
$offset *= $settings['threads_per_page'];
$limit = $settings['threads_per_page'];

$total = $db->count_objects(new TCThread(), $conditions);
// Calculate total pages, rounding up to ensure we can reach all posts.
// TODO: This may need to be refined; ok for now.
$total_pages = ($total <= $settings['threads_per_page']) ? 1 : ceil($total / $settings['threads_per_page']);

$threads = $db->load_objects(new TCThread(), array(), $conditions, $order, $offset, $limit);

foreach ($threads as $thread) {
  $template_data = array(
    'user' => $db->load_object(new TCUser(), $thread->updated_by_user),
    'thread' => $thread,
    'url' => '/?page=' . $settings['page_thread'] . '&amp;thread=' . $thread->thread_id,
    'last_post_date' => date($settings['date_format'], $thread->updated_time)
  );

  TCTemplate::render('thread-preview', $template_data);
}

$page_params = array(
  'page' => $page->page_id,
  'board' => $board->board_id
);

TCTemplate::render('pagination', array('page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']));
