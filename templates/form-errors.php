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

      if ($page->template == 'log-in') {
        if ($error_code == TCObject::ERR_NOT_FOUND) {
          $error_text = 'No match found for the credentials you\'re logging in with.';
        }
      }

      if (empty($error_text)) {
        $error_text = 'A general error has occurred. Please try again later.';
      }

      echo "<li>{$error_text}</li>";
    }
    ?>
  </ul>
</div>
