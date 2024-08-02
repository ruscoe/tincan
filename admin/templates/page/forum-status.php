<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\db\TCData;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;

/**
 * Page template for forum status.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
?>

<h1><?php echo $page->page_title; ?></h1>

<table class="objects">
  <th>Users</th>
  <th>Board Groups</th>
  <th>Boards</th>
  <th>Threads</th>
  <th>Posts</th>
<?php
$db = new TCData();

$total_users = $db->count_objects(new TCUser());
$total_board_groups = $db->count_objects(new TCBoardGroup());
$total_boards = $db->count_objects(new TCBoard());
$total_threads = $db->count_objects(new TCThread());
$total_posts = $db->count_objects(new TCPost());
$data = [
  [
    'type' => 'text',
    'value' => $total_users,
  ],
  [
    'type' => 'text',
    'value' => $total_board_groups,
  ],
  [
    'type' => 'text',
    'value' => $total_boards,
  ],
  [
    'type' => 'text',
    'value' => $total_threads,
  ],
  [
    'type' => 'text',
    'value' => $total_posts,
  ],
];

TCAdminTemplate::render('table-row', $data);
?>
</table>
