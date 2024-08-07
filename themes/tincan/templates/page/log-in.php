<?php

use TinCan\template\TCTemplate;
use TinCan\template\TCURL;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;

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

    switch ($error) {
        case TCUser::ERR_NOT_AUTHORIZED:
            $error_msg = 'Your account can no longer log in.';
            break;
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Could not find an account with that username and password.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
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

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="log_in" value="Log in" />
  </div>

  <?php
    $reset_password_url = TCURL::create_url($settings['page_reset_password']);
?>

  <a href="<?php echo $reset_password_url; ?>">Reset password</a>
</form>
