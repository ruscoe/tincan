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

$url_id = ($settings['enable_urls']) ? $settings['base_url_users'] : $settings['page_user'];
$user_url = TCURL::create_url($url_id, ['user' => $user->user_id], $settings['enable_urls'], $user->get_slug());
?>

<div id="thread-<?php echo $thread->thread_id; ?>" class="thread-preview">
  <h2 class="section-subheader"><a href="<?php echo $data['url']; ?>"><?php echo $thread->thread_title; ?></a></h2>
  <span class="thread-meta last-post-date">Last post by <a href="<?php echo $user_url; ?>"><?php echo $user->username; ?></a> at <?php echo $data['last_post_date']; ?></span>
</div>
