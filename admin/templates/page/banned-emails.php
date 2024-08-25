<?php

use TinCan\db\TCData;
use TinCan\objects\TCBannedEmail;

/**
 * Page template for banned email addresses.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];

$db = new TCData();

$banned_emails = $db->load_objects(new TCBannedEmail());

$banned_email_string = '';

foreach ($banned_emails as $banned_email) {
    $banned_email_string .= $banned_email->email . ' ';
}

?>

<h1><?php echo $page->page_title; ?></h1>

<form id="edit-banned-emails" action="/admin/actions/update-banned-emails.php" method="POST">
  <div class="fieldset textarea">
    <label for="banned_emails">Banned Email Addresses</label>
    <div class="field">
      <textarea name="banned_emails" rows="20" cols="30"><?php echo $banned_email_string; ?></textarea>
      Separate with a space.
    </div>
  </div>

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Update banned Emails" />
  </div>
</form>
