<?php

use TinCan\TCTemplate;
use TinCan\TCURL;

  /**
   * Set password page template.
   *
   * @since 0.07
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $page = $data['page'];
  $settings = $data['settings'];

  $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
  $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
  $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

  TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
  } elseif (!empty($status) && ('set' == $status)) {
    ?>

    <div class="message-box">
      <p>Your new password has been set! <a href="<?php echo TCURL::create_url($settings['page_log_in']); ?>">Log in</a>.</p>
    </div>

    <?php
  }
?>

<?php if (empty($status)) { ?>
<form id="set-password" action="/actions/set-password.php" method="POST">
  <div class="fieldset">
    <label for="password">New password</label>
    <div class="field">
      <input class="text-input" type="password" name="password" />
    </div>
  </div>

  <input type="hidden" name="code" value="<?php echo $code; ?>" />
  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="change_password" value="Set new password" />
  </div>
</form>
<?php } ?>
