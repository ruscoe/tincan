<?php

use TinCan\template\TCURL;
use TinCan\objects\TCUser;

/**
 * Header template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$user = $data['user'];

$create_account_url = TCURL::create_url($settings['page_create_account']);
$log_in_url = TCURL::create_url($settings['page_log_in']);
$log_out_url = TCURL::create_url($settings['page_log_out']);

$title = (!empty($data['page_title'])) ? $data['page_title'] . ' - ' : '';
$title .= $settings['forum_name'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo $settings['forum_logo']; ?>">
    <?php if (!empty($settings['theme'])) {
        include getenv('TC_BASE_PATH')."/themes/{$settings['theme']}/header-include.php";
    } ?>
  </head>
  <body class="tincan <?php echo $data['page_template']; ?>">

<?php
// Check to see if install.php exists on the server.
if (file_exists(getenv('TC_BASE_PATH').'/install.php')) { ?>

  <div id="install-warning">
      install.php is still present on the server. For security reasons, please delete this file.
  </div>

<?php } ?>

    <div id="header">
      <div class="branding">
        <div class="logo">
          <a href="/" title="<?php echo $settings['forum_name']; ?>"><img src="<?php echo $settings['forum_logo']; ?>" alt="<?php echo $settings['forum_name']; ?>" /></a>
        </div>
        <div class="site-name">
          <?php echo $settings['forum_name']; ?>
          <?php if (!empty($settings['forum_tagline'])) { ?>
            <div class="tagline">
              <?php echo $settings['forum_tagline']; ?>
            </div>
          <?php } ?>
        </div>
      </div>
      <ul class="navigation">
      <?php if (empty($user)) { ?>
        <li><a href="<?php echo $create_account_url; ?>">Create Account</a></li>
        <li><a href="<?php echo $log_in_url; ?>">Log In</a></li>
            <?php
      } else {
          $user_url = TCURL::create_url($settings['page_user'], ['user' => $user->user_id]);
          $edit_user_url = TCURL::create_url($settings['page_edit_user'], ['user' => $user->user_id]); ?>
          <li><a href="<?php echo $user_url; ?>">Profile</a></li>
          <li><a href="<?php echo $edit_user_url; ?>">Account</a></li>
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
