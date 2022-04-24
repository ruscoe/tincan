<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCBoard;
use TinCan\TCData;

/**
 * Page template for admin board list.
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

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$boards = $db->load_objects(new TCBoard(), [], $conditions, $order);
?>

<table class="objects">
  <th>Board Name</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($boards as $board) {
  $data = [
    'title' => $board->board_name,
    'object_id' => $board->board_id,
    'view_page_id' => $settings['page_board'],
    'edit_page_id' => $settings['admin_page_edit_board'],
  ];

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
