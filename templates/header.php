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
      <h1>Tin Can Forum</h1>
      <div id="main-navigation">
        <ul>
          <li><a href="/">Home</a></li>
          <?php if (empty($user)) { ?>
            <li><a href="/?page=<?=$settings['page_create_account']?>">Create Account</a></li>
            <li><a href="/?page=<?=$settings['page_log_in']?>">Log In</a></li>
          <?php } else { ?>
            <li><a href="/?page=<?=$settings['page_log_out']?>">Log Out</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
