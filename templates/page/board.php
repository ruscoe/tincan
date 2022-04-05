<?php
$settings = $data['settings'];

$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

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

// TODO: Sorting and pagination.
$threads = $db->load_objects(new TCThread(), array(), $conditions, $order);

foreach ($threads as $thread) {
  $data = array(
    'user' => $db->load_object(new TCUser(), $thread->updated_by_user),
    'thread' => $thread,
    'url' => '/?page=' . $settings['page_thread'] . '&amp;thread=' . $thread->thread_id,
    'last_post_date' => date($settings['date_format'], $thread->updated_time)
  );

  TCTemplate::render('thread-preview', $data);
}
