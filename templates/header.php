<?php
  $settings = $data['settings'];
?>

<html>
  <head>
    <title>Tin Can Forum</title>
  </head>
  <body>
    <div id="header">
      <h1>Tin Can Forum</h1>
      <div id="main-navigation">
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/?page=<?=$settings['page_create_account']?>">Create Account</a></li>
          <li><a href="/?page=<?=$settings['page_login']?>">Log In</a></li>
          <li><a href="/?page=">Log Out</a></li>
        </ul>
      </div>
    </div>
