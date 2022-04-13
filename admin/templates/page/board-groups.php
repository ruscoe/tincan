<?php
/**
 * Page template for admin board group list.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = array();
$order = array();

$board_groups = $db->load_objects(new TCBoardGroup(), array(), $conditions, $order);
?>

<table>
<?php
foreach ($board_groups as $board_group) {
    $data = array(
    'title' => $board_group->board_group_name,
    'object_id' => $board_group->board_group_id,
    'view_page_id' => $settings['page_board_group'],
    'edit_page_id' => $settings['admin_page_edit_board_group']
  );

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
