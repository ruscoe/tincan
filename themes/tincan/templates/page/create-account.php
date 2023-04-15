<?php

use TinCan\TCTemplate;
use TinCan\TCUser;

/**
 * Create account page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$user = $data['user'];
$settings = $data['settings'];

$username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);

if (!$settings['allow_registration']) {
    $error = TCUser::ERR_NOT_AUTHORIZED;
}
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
} elseif (!empty($status) && ('sent' == $status)) {
    ?>

    <div class="message-box">
      <p>Please check your email for your account confirmation link.</p>
    </div>

    <?php
}
?>

<?php if (empty($status) && $settings['allow_registration']) { ?>
<form id="create-account" action="/actions/create-account.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input class="text-input" type="text" name="username" value="<?php echo $username; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email address</label>
    <div class="field">
      <input class="text-input" type="text" name="email" value="<?php echo $email; ?>" />
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
    <input class="submit-button" type="submit" name="submit_thread" value="Create account" />
  </div>
</form>
<?php } ?>
