<?php

use TinCan\TCURL;
use TinCan\TCUser;

/**
 * Header template.
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
    <title><?php echo $settings['forum_name']; ?></title>
    <?php if (!empty($settings['theme'])) {
  include TC_BASE_PATH."/themes/{$settings['theme']}/header-include.php";
} ?>
  </head>
  <body class="tincan <?php echo $data['page_template']; ?>">
    <div id="header">
      <h1><?php echo $settings['forum_name']; ?></h1>
      <ul class="navigation">
      <?php if (empty($user)) { ?>
        <li><a href="<?php echo TCURL::create_url($settings['page_create_account']); ?>">Create Account</a></li>
        <li><a href="<?php echo TCURL::create_url($settings['page_log_in']); ?>">Log In</a></li>
      <?php } else { ?>
        <li>Logged in as <a href="<?php echo TCURL::create_url($settings['page_user'], ['user' => $user->user_id]); ?>"><?php echo $user->username; ?></a></li>
        <?php if (!empty($user) && $user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) { ?>
          <li><a href="/admin">Administration</a></li>
        <?php } ?>
        <li><a href="<?php echo TCURL::create_url($settings['page_log_out']); ?>">Log Out</a></li>
      <?php } ?>
      </ul>
    </div>
    <!-- Start content -->
    <div id="content">
