<?php

$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

$thread = $db->load_object(new TCThread(), $thread_id);
?>

<h1><?=$thread->thread_title?></h1>

<?php

$conditions = array(
  array(
    'field' => 'thread_id',
    'value' => $thread_id
  )
);

$order = array(
  'field' => 'post_id',
  'direction' => 'ASC'
);

// TODO: Set bounds for offset so nothing crazy happens.
$total = $db->count_objects(new TCPost(), $conditions);
$total_pages = TCPagination::calculate_total_pages($total, $settings['posts_per_page']);
$offset = TCPagination::calculate_page_offset($start_at, $settings['posts_per_page']);

$posts = $db->load_objects(new TCPost(), array(), $conditions, $order, $offset, $settings['posts_per_page']);

foreach ($posts as $post) {
    $post_author = $db->load_user($thread->updated_by_user);

    TCTemplate::render('post', array('post' => $post, 'user' => $post_author, 'settings' => $data['settings']));
}

$page_params = array(
  'page' => $page->page_id,
  'thread' => $thread->thread_id
);

TCTemplate::render('pagination', array('page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']));

// Display reply form if user has permission to reply to this thread.
if (!empty($user) && $user->can_perform_action(TCUser::ACT_CREATE_POST)) {
    TCTemplate::render('post-reply', array('thread' => $thread, 'user' => $user));
}
