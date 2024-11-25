<?php

use TinCan\template\TCURL;

/**
 * Thread template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];
$thread = $data['thread'];
$settings = $data['settings'];
$count = $data['post_count'];

$user_url = TCURL::create_url($settings['page_user'], ['user' => $user->user_id]);
?>

<div id="thread-<?php echo $thread->thread_id; ?>" class="thread-preview">
  <h2 class="section-subheader"><a href="<?php echo $data['url']; ?>"><?php echo $thread->thread_title; ?></a></h2>
  <span><?php echo $count; ?> posts</span>,<span class="thread-meta last-post-date">Last post by <a href="<?php echo $user_url; ?>"><?php echo $user->username; ?></a> at <?php echo $data['last_post_date']; ?></span>
</div>
