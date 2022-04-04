<?php
  $page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

<form action="/actions/create-account.php" method="POST">
  <label for="username">Username</label>
  <input type="text" name="username" />

  <label for="email">Email address</label>
  <input type="text" name="email" />

  <label for="password">Password</label>
  <input type="password" name="password" />

  <input type="submit" name="submit_thread" value="Create account" />
</form>
