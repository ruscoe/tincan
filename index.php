<?php

require 'tc-config.php';

require 'includes/include-db.php';
require 'includes/include-objects.php';
require 'includes/include-template.php';

TCTemplate::render('header', NULL);

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

TCTemplate::render('footer', NULL);
