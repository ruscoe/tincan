<?php
  $page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<form action="/actions/log-in.php" method="POST">
  <input type="text" name="username" />
  <input type="password" name="password" />
  <input type="submit" name="submit_thread" value="Log in" />
</form>
