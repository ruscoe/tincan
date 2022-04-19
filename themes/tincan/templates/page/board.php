<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCPagination;
use TinCan\TCUser;
use TinCan\TCTemplate;
use TinCan\TCThread;

/**
 * Board page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];
$user = $data['user'];

$db = new TCData();

$board = $db->load_object(new TCBoard(), $board_id);

TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $board, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $board->board_name; ?></h1>

<?php
  // Show new thread link if user has permission to create a new thread.
  if (!empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
    ?>

  <div id="board-navigation">
    <ul class="navigation">
      <li><a href="/?page=<?php echo $settings['page_new_thread']; ?>&board=<?php echo $board->board_id; ?>">New thread</a></li>
    </ul>
  </div>

<?php
  }
?>

<?php
// Get threads in this board; order by thread with most recent post.
$conditions = [
  [
    'field' => 'board_id',
    'value' => $board->board_id,
  ],
];

$order = [
  'field' => 'updated_time',
  'direction' => 'DESC',
];

// TODO: Set bounds for offset so nothing crazy happens.
$total = $db->count_objects(new TCThread(), $conditions);
$total_pages = TCPagination::calculate_total_pages($total, $settings['threads_per_page']);
$offset = TCPagination::calculate_page_offset($start_at, $settings['threads_per_page']);

$threads = $db->load_objects(new TCThread(), [], $conditions, $order, $offset, $settings['threads_per_page']);

foreach ($threads as $thread) {
  $template_data = [
    'user' => $db->load_user($thread->updated_by_user),
    'thread' => $thread,
    'url' => '/?page='.$settings['page_thread'].'&amp;thread='.$thread->thread_id,
    'last_post_date' => date($settings['date_time_format'], $thread->updated_time),
  ];

  TCTemplate::render('thread-preview', $settings['theme'], $template_data);
}

$page_params = [
  'page' => $page->page_id,
  'board' => $board->board_id,
];

TCTemplate::render('pagination', $settings['theme'], ['page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']]);
