<?php

$db = new TCData();

$board_groups = $db->load_objects(new TCBoardGroup());

foreach ($board_groups as $group) {
  $board_conditions = array(
    array(
      'field' => 'board_group_id',
      'value' => $group->board_group_id
    )
  );

  $boards = $db->load_objects(new TCBoard(), array(), $board_conditions);

  $data = array(
    'board_group' => $group,
    'boards' => $boards
  );

  TCTemplate::render('board-group', $data);
}
