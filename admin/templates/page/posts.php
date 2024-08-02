<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCUser;
use TinCan\objects\TCThread;
use TinCan\objects\TCPost;
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
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$conditions = [];

// TODO Sorting and pagination.
$order = [];

$db = new TCData();

$posts = $db->load_objects(new TCPost(), [], $conditions, $order);
?>

<table class="objects">
  <th>User</th>
  <th>Thread</th>
  <th>Post</th>
<?php
foreach ($posts as $post) {
    $user = $db->load_object(new TCUser(), $post->user_id);
    $thread = $db->load_object(new TCThread(), $post->thread_id);

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
      'url' => '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id.'#post-'.$post->post_id,
      // Show trimmed post content.
      'value' => substr($post->content, 0, 50).'...',
    ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
