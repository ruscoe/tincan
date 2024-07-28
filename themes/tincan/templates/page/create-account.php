<?php

use TinCan\template\TCTemplate;
use TinCan\objects\TCUser;

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

    switch ($error) {
        case TCUser::ERR_USER:
            $error_msg = 'Please choose a longer username.';
            break;
        case TCUser::ERR_EMAIL:
            $error_msg = 'Please check your email address has been entered correctly.';
            break;
        case TCUser::ERR_PASSWORD:
            $error_msg = 'Please choose a longer password.';
            break;
        case TCUser::ERR_USERNAME_EXISTS:
            $error_msg = 'This username already exists; please choose another.';
            break;
        case TCUser::ERR_EMAIL_EXISTS:
            $error_msg = 'An account with this email address already exists.';
            break;
        case TCUser::ERR_NOT_AUTHORIZED:
            $error_msg = 'Account registration is currently disabled.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Could not create an account. Please try again later.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
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

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_thread" value="Create account" />
  </div>
</form>
<?php } ?>
