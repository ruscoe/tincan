<?php
  $settings = $data['settings'];
  $user = $data['user'];
?>

<html>
  <head>
    <title>Tin Can Forum</title>
  </head>
  <body>
    <div id="header">
      <h1>Tin Can Forum Admin</h1>
      <div id="user-info">
        <ul>
          <li>Logged in as <?=$user->username?> at <?=date($settings['date_format'], time())?></li>
        </ul>
      </div>
      <div id="main-navigation">
        <ul>
          <li><a href="/admin">Dashboard</a></li>
          <li><a href="/admin?page=<?=$settings['page_forum_settings']?>">Settings</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_board_groups']?>">Board groups</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_boards']?>">Boards</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_threads']?>">Threads</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_posts']?>">Posts</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_pages']?>">Pages</a></li>
          <li><a href="/admin?page=<?=$settings['page_admin_users']?>">Users</a></li>
          <li><a href="/admin?page=">Log out</a></li>
        </ul>
      </div>
    </div>
