<?php
/**
 * Board group page template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$board_group_id = filter_input(INPUT_GET, 'board_group', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);

?>

<h1 class="section-header"><?=$board_group->board_group_name?></h1>

<?php

$settings = $db->load_settings();

// Get boards in this board group; order by board name.
$conditions = array(
  array(
    'field' => 'board_group_id',
    'value' => $board_group->board_group_id
  )
);

$order = array(
  'field' => 'board_name',
  'direction' => 'ASC'
);

// TODO: Sorting and pagination.
$boards = $db->load_objects(new TCBoard(), array(), $conditions, $order);

foreach ($boards as $board) {
    $data = array(
      'board' => $board,
      'url' => '/?page=' . $settings['page_board'] . '&amp;board=' . $board->board_id
    );

    TCTemplate::render('board-preview', $data);
}
