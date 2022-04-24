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
          <li>Logged in as <?php echo $user->username; ?> at <?php echo date($settings['date_time_format'], time()); ?></li>
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
          <li><a href="/admin">Dashboard</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_forum_settings']; ?>">Settings</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_board_groups']; ?>">Board groups</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_boards']; ?>">Boards</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_threads']; ?>">Threads</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_posts']; ?>">Posts</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_pages']; ?>">Pages</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_users']; ?>">Users</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_phpinfo']; ?>">PHP Info</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_log_out']; ?>">Log out</a></li>
        </ul>
      </div>
      <!-- Start content -->
      <div id="content">
