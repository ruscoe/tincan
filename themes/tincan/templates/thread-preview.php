<?php
/**
 * Thread template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];
$thread = $data['thread'];
?>

<div id="thread-<?php echo $thread->thread_id; ?>" class="thread-preview">
  <h2 class="section-subheader"><a href="<?php echo $data['url']; ?>"><?php echo $thread->thread_title; ?></a></h2>
  <span class="thread-meta last-post-date">Last post by <?php echo $user->username; ?> at <?php echo $data['last_post_date']; ?></span>
</div>
