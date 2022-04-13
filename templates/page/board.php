<?php
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

$board = $db->load_object(new TCBoard(), $board_id);
?>

<h1><?=$board->board_name?></h1>

<?php
  // Show new thread link if user has permission to create a new thread.
  if (!empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
      ?>

  <div id="board-navigation">
    <ul class="navigation">
      <li><a href="/?page=<?=$settings['page_new_thread']?>&board=<?=$board->board_id?>">New thread</a></li>
    </ul>
  </div>

<?php
  }
?>

<?php
// Get threads in this board; order by thread with most recent post.
$conditions = array(
  array(
    'field' => 'board_id',
    'value' => $board->board_id
  )
);

$order = array(
  'field' => 'updated_time',
  'direction' => 'DESC'
);

// TODO: Set bounds for offset so nothing crazy happens.
$total = $db->count_objects(new TCThread(), $conditions);
$total_pages = TCPagination::calculate_total_pages($total, $settings['threads_per_page']);
$offset = TCPagination::calculate_page_offset($start_at, $settings['threads_per_page']);

$threads = $db->load_objects(new TCThread(), array(), $conditions, $order, $offset, $settings['threads_per_page']);

foreach ($threads as $thread) {
    $template_data = array(
    'user' => $db->load_user($thread->updated_by_user),
    'thread' => $thread,
    'url' => '/?page=' . $settings['page_thread'] . '&amp;thread=' . $thread->thread_id,
    'last_post_date' => date($settings['date_time_format'], $thread->updated_time)
  );

    TCTemplate::render('thread-preview', $template_data);
}

$page_params = array(
  'page' => $page->page_id,
  'board' => $board->board_id
);

TCTemplate::render('pagination', array('page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']));
