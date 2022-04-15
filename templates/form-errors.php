<?php
/**
 * Form errors template.
 *
 * @since 0.02
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$errors = $data['errors'];
?>

<div class="message-box errors">
  <ul>
    <?php
    foreach ($errors as $error_code) {
      $error_text = '';

      switch ($error_code) {
        case TCObject::ERR_NOT_FOUND:
        case TCObject::ERR_NOT_SAVED:
          $error_text = 'TODO: '.$error_code;
          break;
        case TCUser::ERR_USER:
          $error_text = 'TODO: '.$error_code;
          break;
        case TCUser::ERR_PASSWORD:
          $error_text = 'TODO: '.$error_code;
          break;
        default:
          $error_text = 'A general error has occurred. Please try again later.';
      }

      echo "<li>{$error_text}</li>";
    }
    ?>
  </ul>
</div>
