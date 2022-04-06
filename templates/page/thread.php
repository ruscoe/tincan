<?php

$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);
$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

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
//   Calculate maximum page number from posts.
//   Avoid negative numbers.
// $start_at is the page number. Records start at 0, so page 1 is technically 0.
// Subtract 1 from $start_at
$offset = ($start_at > 1) ? ($start_at - 1) : 0;
$offset *= $settings['posts_per_page'];
$limit = $settings['posts_per_page'];

$total = $db->count_objects(new TCPost(), $conditions);
// Calculate total pages, rounding up to ensure we can reach all posts.
// TODO: This may need to be refined; ok for now.
$total_pages = ($total <= $settings['posts_per_page']) ? 1 : ceil($total / $settings['posts_per_page']);

$posts = $db->load_objects(new TCPost(), array(), $conditions, $order, $offset, $limit);

foreach ($posts as $post) {
  $user = $db->load_object(new TCUser(), $post->user_id);

  TCTemplate::render('post', array('post' => $post, 'user' => $user, 'settings' => $data['settings']));
}

$page_params = array(
  'page' => $page->page_id,
  'thread' => $thread->thread_id
);

TCTemplate::render('pagination', array('page_params' => $page_params, 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $data['settings']));
