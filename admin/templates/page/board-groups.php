<?php
/**
 * Page template for admin board group list.
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

$board_groups = $db->load_objects(new TCBoardGroup(), [], $conditions, $order);
?>

<table>
<?php
foreach ($board_groups as $board_group) {
  $data = [
    'title' => $board_group->board_group_name,
    'object_id' => $board_group->board_group_id,
    'view_page_id' => $settings['page_board_group'],
    'edit_page_id' => $settings['admin_page_edit_board_group'],
  ];

  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
