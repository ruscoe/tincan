<?php

/**
 * Page template for testing email.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
?>

<h1>Test Email</h1>

<form id="edit-page" action="/admin/actions/test-mail.php" method="POST">
  <div class="fieldset">
    <label for="page_title">Recipient</label>
    <div class="field">
      <input type="text" name="recipient" value="" />
    </div>
  </div>

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Send test email" />
  </div>
</form>
