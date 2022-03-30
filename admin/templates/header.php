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
          <li><a href="/">Dashboard</a></li>
          <li><a href="/">Board groups</a></li>
          <li><a href="/">Boards</a></li>
          <li><a href="/">Threads</a></li>
          <li><a href="/">Posts</a></li>
          <li><a href="/">Log out</a></li>
        </ul>
      </div>
    </div>
