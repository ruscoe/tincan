<?php

use TinCan\TCTemplate;
use TinCan\TCURL;

/**
 * Log in page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$user = $data['user'];
$settings = $data['settings'];

$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
  }
?>

<form id="log-in" action="/actions/log-in.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input class="text-input" type="text" name="username" />
    </div>
  </div>

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input class="text-input" type="password" name="password" />
    </div>
  </div>

  <input class="ajax" type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="log_in" value="Log in" />
  </div>

  <?php
    if ($settings['enable_urls']) {
      $reset_password_url = '/reset-password';
    } else {
      $reset_password_url = TCURL::create_url($settings['page_reset_password']);
    }
  ?>

  <a href="<?php echo $reset_password_url; ?>">Reset password</a>
</form>
