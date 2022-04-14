<?php
/**
 * Thread template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$user = $data['user'];
$thread = $data['thread'];
?>

<div id="thread-<?=$thread->thread_id?>" class="thread-preview">
  <h2 class="section-subheader"><a href="<?=$data['url']?>"><?=$thread->thread_title?></a></h2>
  <span class="thread-meta last-post-date">Last post by <?=$user->username?> at <?=$data['last_post_date']?></span>
</div>
