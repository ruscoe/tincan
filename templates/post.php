<?php
  $post = $data['post'];
  $user = $data['user'];
  $settings = $data['settings'];
?>

<div id="post-<?=$post->post_id?>" class="post">
  <div class="post-user">
    <span class="username"><?=$user->username?></span>
    <span class="joined">Joined: <?=date($settings['date_format'], $user->created_time)?></span>
  </div>
  <div class="post-content">
    <span class="date"><?=date($settings['date_format'], $post->created_time)?></span>
    <div><?=$post->content?></div>
  </p>
</div>
