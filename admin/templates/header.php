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
  </head>
  <body>
    <div id="header">
      <h1>Tin Can Forum Admin</h1>
      <div id="user-info">
        <ul>
          <li>Logged in as <?php echo $user->username; ?> at <?php echo date($settings['date_format'], time()); ?></li>
        </ul>
      </div>
      <div id="main-navigation">
        <ul>
          <li><a href="/admin">Dashboard</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_forum_settings']; ?>">Settings</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_board_groups']; ?>">Board groups</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_boards']; ?>">Boards</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_threads']; ?>">Threads</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_posts']; ?>">Posts</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_pages']; ?>">Pages</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_users']; ?>">Users</a></li>
          <li><a href="/admin?page=<?php echo $settings['admin_page_log_out']; ?>">Log out</a></li>
        </ul>
      </div>
    </div>
