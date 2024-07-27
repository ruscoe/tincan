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
foreach ($errors as $error) {
    echo "<li>{$error}</li>";
}
?>
  </ul>
</div>
