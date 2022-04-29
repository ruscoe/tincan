<?php
/**
 * Admin header template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $settings = $data['settings'];
  $user = $data['user'];

  // TODO: Check user role before anything else.
?>

<html>
  <head>
    <title>Tin Can Forum</title>
    <link href="/admin/css/style.css" rel="stylesheet">
  </head>
  <body class="tincan-admin">
    <div id="header">
      <div id="user-info">
        <ul class="navigation">
          <li>Tin Can Forum version <?php echo TC_VERSION; ?></li>
          <li>Logged in as <?php echo $user->username; ?></li>
          <li><a href="/actions/log-out.php">Log Out</a></li>
          <li><a href="/" target="_blank">View Forum</a></li>
        </ul>
      </div>
    </div>
    <!-- Start page -->
    <div id="page">
      <div id="navigation">
        <a href="/admin"><img class="admin-logo" src="/admin/images/tc-logo.png" /></a>
        <ul>
          <li><a href="/admin?page=<?php echo $settings['admin_page_forum_settings']; ?>">Settings</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_board_groups']; ?>">Board Groups</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_boards']; ?>">Boards</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_threads']; ?>">Threads</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_pages']; ?>">Pages</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_users']; ?>">Users</a></li>
        </ul>
      </div>
      <!-- Start content -->
      <div id="content">
