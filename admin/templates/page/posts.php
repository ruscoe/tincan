<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCUser;
use TinCan\objects\TCThread;
use TinCan\objects\TCPost;
use TinCan\template\TCPagination;
use TinCan\db\TCData;

/**
 * Page template for admin post list.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$settings = $data['settings'];

$start_at = filter_input(INPUT_GET, 'start_at', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$conditions = [];
$order = [
  [
    'field' => 'post_id',
    'direction' => 'DESC',
  ],
];

$db = new TCData();

$total = $db->count_objects(new TCPost(), $conditions);
$total_pages = TCPagination::calculate_total_pages($total, $settings['posts_per_page']);
$offset = TCPagination::calculate_page_offset($start_at, $settings['posts_per_page']);

$posts = $db->load_objects(new TCPost(), [], $conditions, $order, $offset, $settings['posts_per_page']);
?>

<table class="objects">
  <th>User</th>
  <th>Thread</th>
  <th>Post</th>
<?php
foreach ($posts as $post) {
    $user = $db->load_object(new TCUser(), $post->user_id);
    $thread = $db->load_object(new TCThread(), $post->thread_id);

    $start_at = $db->get_post_page_in_thread($thread->thread_id, $post->post_id, $settings['posts_per_page']);

    $data = [
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_user'].'&user='.$user->user_id,
      'value' => $user->username,
    ],
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id,
      // Show trimmed thread title.
      'value' => substr($thread->thread_title, 0, 50).'...',
    ],
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id.'&start_at='.$start_at.'#post-'.$post->post_id,
      // Show trimmed post content.
      'value' => substr($post->content, 0, 50).'...',
    ],
    ];

    TCAdminTemplate::render('table-row', $data);
}

?>
</table>

<?php
  TCAdminTemplate::render('pagination', ['page_params' => ['page' => $page->page_id], 'start_at' => $start_at, 'total_pages' => $total_pages, 'settings' => $settings]);
?>
