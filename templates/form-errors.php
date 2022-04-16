<?php
/**
 * Form errors template.
 *
 * @since 0.02
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$errors = $data['errors'];
$page = $data['page'];
?>

<div class="message-box">
  <ul class="errors">
    <?php
    foreach ($errors as $error_code) {
      $error_text = '';

      if ('log-in' == $page->template) {
        if (TCObject::ERR_NOT_FOUND == $error_code) {
          $error_text = 'No match found for the credentials you\'re logging in with.';
        }
      }

      if ('create-account' == $page->template) {
        if (TCUser::ERR_USER == $error_code) {
          $error_text = 'Please choose a username longer than '.TCUser::MIN_USERNAME_LENGTH.' characters.';
        } elseif (TCUser::ERR_EMAIL == $error_code) {
          $error_text = 'Please check your email address has been entered correctly.';
        } elseif (TCUser::ERR_PASSWORD == $error_code) {
          $error_text = 'Please choose a password longer than '.TCUser::MIN_PASSWORD_LENGTH.' characters.';
        } elseif (TCUser::ERR_USERNAME_EXISTS == $error_code) {
          $error_text = 'The username you entered has been taken. Please choose another.';
        } elseif (TCUser::ERR_EMAIL_EXISTS == $error_code) {
          $error_text = 'The email address you entered is already in use. You may already have an account.';
        }
      }

      if (empty($error_text)) {
        $error_text = 'A general error has occurred. Please try again later. ('.$error_code.')';
      }

      echo "<li>{$error_text}</li>";
    }
    ?>
  </ul>
</div>
