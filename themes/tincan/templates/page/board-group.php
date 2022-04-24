<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCTemplate;

$settings = $data['settings'];

/**
 * Board group page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_group_id = filter_input(INPUT_GET, 'board_group', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);

TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $board_group, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $board_group->board_group_name; ?></h1>

<?php

$settings = $db->load_settings();

// Get boards in this board group; order by board name.
$conditions = [
  [
    'field' => 'board_group_id',
    'value' => $board_group->board_group_id,
  ],
];

$order = [
  'field' => 'board_name',
  'direction' => 'ASC',
];

// TODO: Sorting and pagination.
$boards = $db->load_objects(new TCBoard(), [], $conditions, $order);

foreach ($boards as $board) {
  $data = [
      'board' => $board,
      'url' => TCURL::create_url($settings['page_board'], ['board' => $board->board_id]),
    ];

  TCTemplate::render('board-preview', $settings['theme'], $data);
}
