<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\objects\TCBoard;
use TinCan\objects\TCReport;
use TinCan\objects\TCUser;
use TinCan\objects\TCThread;
use TinCan\objects\TCPost;
use TinCan\db\TCData;

/**
 * Page template for admin reported post list.
 *
 * @since 0.16
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

$reports = $db->load_objects(new TCReport(), [], $conditions, $order);
?>

<table class="objects">
  <th>User</th>
  <th>Thread</th>
  <th>Post</th>
  <th>Reason</th>
  <th>&nbsp;</th>
<?php
foreach ($reports as $report) {
    $user = $db->load_object(new TCUser(), $report->user_id);
    $post = $db->load_object(new TCPost(), $report->post_id);
    $thread = $db->load_object(new TCThread(), $post->thread_id);

    $data = [
    [
      'type' => 'link',
      'url' => '/index.php?page='.$settings['page_user'].'&user='.$user->user_id,
      'value' => $user->username,
    ],
    [
      'type' => 'link',
      'url' => (!empty($thread)) ? '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id : '#',
      // Show trimmed thread title.
      'value' => (!empty($thread)) ? substr($thread->thread_title, 0, 50).'...' : '[DELETED]',
    ],
    [
      'type' => 'link',
      'url' => (!empty($thread)) ? '/index.php?page='.$settings['page_thread'].'&thread='.$thread->thread_id.'#post-'.$post->post_id : '#',
      // Show trimmed post content.
      'value' => substr($post->content, 0, 50).'...',
    ],
    [
      'type' => 'text',
      'value' => $report->reason,
    ],
    [
      'type' => 'link',
      'url' => '/admin/index.php?page='.$settings['admin_page_delete_report'].'&report_id='.$report->report_id,
      'value' => 'Delete',
    ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
