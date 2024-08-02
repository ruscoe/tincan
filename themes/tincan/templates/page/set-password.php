<?php

use TinCan\template\TCTemplate;
use TinCan\template\TCURL;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;

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

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => null]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($error)) {
    switch ($error) {
        case TCUser::ERR_PASSWORD:
            $error_msg = 'Please choose a longer password.';
            break;
        case TCObject::ERR_NOT_FOUND:
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Could not set your password at this time. Please try again later.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
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

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="change_password" value="Set new password" />
  </div>
</form>
<?php } ?>
