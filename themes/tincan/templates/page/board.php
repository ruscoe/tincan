<?php

use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\template\TCPagination;
use TinCan\template\TCTemplate;
use TinCan\objects\TCThread;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;

/**
 * Board page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$settings = $data['settings'];
$slug = $data['slug'];
$page = $data['page'];
$user = $data['user'];

$db = new TCData();

if (!empty($board_id)) {
    $board = $db->load_object(new TCBoard(), $board_id);
} elseif (!empty($slug)) {
    $matched_boards = $db->load_objects(new TCBoard(), null, [['field' => 'slug', 'value' => $slug]]);
    $board = reset($matched_boards);
}

if (empty($board)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $board->get_name(), 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $board, 'settings' => $settings]);
?>

<?php
  // Show new thread link if user has permission to create a new thread.
if (!empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
    $url_id = ($settings['enable_urls']) ? $settings['base_url_new_thread'] : $settings['page_new_thread'];
    $new_thread_url = TCURL::create_url($url_id, ['board' => $board->board_id], $settings['enable_urls'], $board->get_slug()); ?>

  <div id="board-navigation">
    <ul class="navigation">
      <li><a class="button" href="<?php echo $new_thread_url; ?>">New thread</a></li>
    </ul>
  </div>
    <?php
}
?>

<h1 class="section-header"><?php echo $board->board_name; ?></h1>

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

$thread_url = null;
if (!empty($threads)) {
    foreach ($threads as $thread) {
        $url_id = ($settings['enable_urls']) ? $settings['base_url_threads'] : $settings['page_thread'];
        $thread_url = TCURL::create_url($url_id, ['thread' => $thread->thread_id], $settings['enable_urls'], $thread->get_slug());

        $template_data = [
          'user' => $db->load_user($thread->updated_by_user),
          'thread' => $thread,
          'url' => $thread_url,
          'last_post_date' => date($settings['date_time_format'], $thread->updated_time),
          'settings' => $settings,
        ];

        TCTemplate::render('thread-preview', $settings['theme'], $template_data);
    }
} else {
    ?>
  <div class="message-box">
    <p>No threads here!</p>
    <?php
      // Show new thread link if user has permission to create a new thread.
    if (!empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
        ?>
      <p>You can <a href="<?php echo TCURL::create_url($settings['page_new_thread'], ['board' => $board->board_id]); ?>">create the first one!</a></p>
        <?php
    } else {
        ?>
      <p>You can <a href="<?php echo TCURL::create_url($settings['page_create_account']); ?>">create an account and change that!</a></p>
        <?php
    } ?>
  </div>
    <?php
}

$page_params = [
  'page' => $page->page_id,
  'board' => $board->board_id,
];

TCTemplate::render('pagination', $settings['theme'], ['page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']]);
