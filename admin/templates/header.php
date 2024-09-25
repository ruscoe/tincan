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
    <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/admin/images/tin-can-logo.png" rel="icon">
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
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
        <a href="/admin" class="admin-logo"><img src="/admin/images/tin-can-logo.png" /></a>

        <?php
          $nav_items = [
            '/admin?page='.$settings['admin_page_forum_status'] => 'Status',
            '/admin?page='.$settings['admin_page_forum_settings'] => 'Settings',
            '/admin?page='.$settings['admin_page_users'] => 'Users',
            '/admin?page='.$settings['admin_page_board_groups'] => 'Board Groups',
            '/admin?page='.$settings['admin_page_boards'] => 'Boards',
            '/admin?page='.$settings['admin_page_threads'] => 'Threads',
            '/admin?page='.$settings['admin_page_posts'] => 'Posts',
            '/admin?page='.$settings['admin_page_reports'] => 'Reported Posts',
            '/admin?page='.$settings['admin_page_mail_templates'] => 'Mail Templates',
            '/admin?page='.$settings['admin_page_test_mail'] => 'Test Email',
            '/admin?page='.$settings['admin_page_banned_ips'] => 'Banned IPs',
            '/admin?page='.$settings['admin_page_banned_emails'] => 'Banned Emails',
          ];
?>

        <ul>
          <?php foreach ($nav_items as $url => $title): ?>
            <li><a href="<?php echo $url; ?>" class="<?php if ($url === '/admin?page=' . $data['page_id']) {
                echo "active";
            } ?>"><?php echo $title; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <!-- Start content -->
      <div id="content">
