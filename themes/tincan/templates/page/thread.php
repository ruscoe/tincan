<?php

use TinCan\db\TCData;
use TinCan\template\TCPagination;
use TinCan\objects\TCPost;
use TinCan\template\TCTemplate;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;
use TinCan\template\TCURL;

/**
 * Thread page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$settings = $data['settings'];
$page = $data['page'];
$user = $data['user'];

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

if (empty($thread)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $thread->get_name(), 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $thread, 'settings' => $settings]);
?>

<?php
  // Show delete thread link if user has permission to delete the thread.
if (!empty($user) && $user->can_delete_thread($thread)) {
    $delete_thread_url = TCURL::create_url($settings['page_delete_thread'], ['thread' => $thread->thread_id]); ?>

  <div id="thread-navigation">
    <ul class="navigation">
      <li><a class="button" href="<?php echo $delete_thread_url; ?>">Delete thread</a></li>
    </ul>
  </div>
    <?php
}
?>

<h1 class="section-header"><?php echo $thread->thread_title; ?></h1>

<?php

$conditions = [
  [
    'field' => 'thread_id',
    'value' => $thread_id,
  ],
];

$order = [
  [
  'field' => 'post_id',
  'direction' => 'ASC',
  ],
];

// TODO: Set bounds for offset so nothing crazy happens.
$total = $db->count_objects(new TCPost(), $conditions);
$total_pages = TCPagination::calculate_total_pages($total, $settings['posts_per_page']);
$offset = TCPagination::calculate_page_offset($start_at, $settings['posts_per_page']);

$posts = $db->load_objects(new TCPost(), [], $conditions, $order, $offset, $settings['posts_per_page']);

foreach ($posts as $post) {
    if ($post->deleted) {
        TCTemplate::render('deleted-post', $settings['theme'], ['post' => $post]);
    } else {
        $author = $db->load_user($post->user_id);
        TCTemplate::render('post', $settings['theme'], ['thread' => $thread, 'page_number' => $start_at, 'post' => $post, 'author' => $author, 'user' => $user, 'settings' => $data['settings']]);
    }
}

$page_params = [
  'page' => $page->page_id,
  'thread' => $thread->thread_id,
];

TCTemplate::render('pagination', $settings['theme'], ['page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']]);

// Display reply form if user has permission to reply to this thread.
if (!$thread->locked && !empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_POST)) {
    TCTemplate::render('post-reply', $settings['theme'], ['thread' => $thread, 'user' => $user, 'page' => $page, 'settings' => $settings]);
}

if ($thread->locked) {
    ?>
  <div class="thread-notice">This thread is locked and cannot be replied to.</div>
    <?php
}
