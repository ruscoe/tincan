<?php

use TinCan\TCTemplate;

/**
 * Reset password page template.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$user = $data['user'];
$settings = $data['settings'];

$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
} elseif (!empty($status) && ('sent' == $status)) {
    ?>

    <div class="message-box">
      <p>Please check your email for your password reset link.</p>
    </div>

    <?php
}
?>

<?php if (empty($status)) { ?>
<form id="log-in" action="/actions/reset-password.php" method="POST">
  <div class="fieldset">
    <label for="email">Email address</label>
    <div class="field">
      <input class="text-input" type="text" name="email" />
    </div>
  </div>

  <input class="ajax" type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="reset_password" value="Reset password" />
  </div>
</form>
<?php } ?>
