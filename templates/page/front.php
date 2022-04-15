<?php
/**
 * Front page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$db = new TCData();

$settings = $db->load_settings();

$board_groups = $db->load_objects(new TCBoardGroup());

foreach ($board_groups as $group) {
  $board_conditions = [
    [
      'field' => 'board_group_id',
      'value' => $group->board_group_id,
    ],
  ];

  $boards = $db->load_objects(new TCBoard(), [], $board_conditions);

  $data = [
    'settings' => $settings,
    'board_group' => $group,
    'boards' => $boards,
  ];

  TCTemplate::render('board-group', $data);
}
