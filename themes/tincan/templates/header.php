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

if ($settings['enable_urls']) {
  $create_account_url = '/create-account';
  $log_in_url = '/log-in';
  $log_out_url = '/log-out';
} else {
  $create_account_url = TCURL::create_url($settings['page_create_account']);
  $log_in_url = TCURL::create_url($settings['page_log_in']);
  $log_out_url = TCURL::create_url($settings['page_log_out']);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $settings['forum_name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!empty($settings['theme'])) {
  include TC_BASE_PATH."/themes/{$settings['theme']}/header-include.php";
} ?>
  </head>
  <body class="tincan <?php echo $data['page_template']; ?>">
    <div id="header">
      <h1><?php echo $settings['forum_name']; ?></h1>
      <ul class="navigation">
      <?php if (empty($user)) { ?>
        <li><a href="<?php echo $create_account_url; ?>">Create Account</a></li>
        <li><a href="<?php echo $log_in_url; ?>">Log In</a></li>
      <?php
      } else {
        $user_url = ($settings['enable_urls']) ? TCURL::create_friendly_url($settings['base_url_users'], $user) : TCURL::create_url($settings['page_user'], ['user' => $user->user_id]); ?>
        <li>Logged in as <a href="<?php echo $user_url; ?>"><?php echo $user->username; ?></a></li>
        <?php if (!empty($user) && $user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) { ?>
          <li><a href="/admin">Administration</a></li>
        <?php } ?>
        <li><a href="<?php echo $log_out_url; ?>">Log Out</a></li>
      <?php
      } ?>
      </ul>
    </div>
    <!-- Start content -->
    <div id="content">
