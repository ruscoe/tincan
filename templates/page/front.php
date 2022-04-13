<?php
/**
 * Front page template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$db = new TCData();

$settings = $db->load_settings();

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
    'settings' => $settings,
    'board_group' => $group,
    'boards' => $boards
  );

    TCTemplate::render('board-group', $data);
}
