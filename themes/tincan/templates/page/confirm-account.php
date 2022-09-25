<?php

use TinCan\TCTemplate;
use TinCan\TCURL;

/**
 * Account confirmation page template.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$page = $data['page'];
$user = $data['user'];
$error = $data['error'];

TCTemplate::render('header', $settings['theme'], ['page_title' => 'Account Confirmation', 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
?>

<h1 class="section-header">Account Confirmation</h1>

<?php
  if (!empty($error)) {
      ?>
  <p>Unable to confirm your account. Please check the link in your email.</p>
<?php
  } else {
      ?>
  <p>Your account has been confirmed and you are now logged in!</p>
<?php
  }
?>

<p><a href="<?php echo TCURL::create_url(null); ?>">Return to the forum</a></p>
