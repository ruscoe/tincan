<?php
  $settings = $data['settings'];
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
          <li>Logged in as {{admin}} at <?=date($settings['date_format'], time())?></li>
        </ul>
      </div>
      <div id="main-navigation">
        <ul>
          <li><a href="/admin">Dashboard</a></li>
          <li><a href="/admin?page=9">Board groups</a></li>
          <li><a href="/admin?page=10">Boards</a></li>
          <li><a href="/admin?page=11">Threads</a></li>
          <li><a href="/admin?page=12">Posts</a></li>
          <li><a href="/admin?page=13">Pages</a></li>
          <li><a href="/admin?page=14">Users</a></li>
          <li><a href="/admin?page=">Log out</a></li>
        </ul>
      </div>
    </div>
