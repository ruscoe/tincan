<?php
  $thread = $data['thread'];
?>

<div id="thread-<?=$thread->thread_id?>" class="thread-preview">
  <h2><a href="<?=$data['url']?>"><?=$thread->thread_title?></a></h2>
  <span class="thread-meta last-post-date"><?=$data['last_post_date']?></span>
</div>
