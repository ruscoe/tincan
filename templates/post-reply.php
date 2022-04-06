<?php
  $thread = $data['thread'];
  $user = $data['user'];
?>

<form action="/actions/create-post.php" method="POST">
  <textarea name="post_content" rows="20" cols="30"></textarea>
  <input type="hidden" name="thread_id" value="<?=$thread->thread_id?>" />
  <input type="submit" name="submit_post" value="Submit reply" />
</form>
