<?php

use TinCan\TCErrorMessage;
use TinCan\TCObject;
use TinCan\TCUser;

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
    $error_message = new TCErrorMessage();

    foreach ($errors as $error_code) {
        $error_text = $error_message->get_error_message($page->template, $error_code);

        echo "<li>{$error_text}</li>";
    }
?>
  </ul>
</div>
