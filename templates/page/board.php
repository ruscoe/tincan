<?php

$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$board = $db->load_object(new TCBoard(), $board_id);

?>

<h1><?=$board->board_name?></h1>

<?php

$conditions = array(
  array(
    'field' => 'board_id',
    'value' => $board_id
  )
);

// TODO: Sort by thread with most recent post.
$threads = $db->load_objects(new TCThread(), array(), $conditions);

foreach ($threads as $thread) {
  TCTemplate::render('thread-preview', array('thread' => $thread));
}
