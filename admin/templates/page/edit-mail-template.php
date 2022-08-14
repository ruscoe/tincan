<?php

use TinCan\TCData;
use TinCan\TCMailTemplate;

/**
 * Page template for editing mail templates.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$mail_template_id = filter_input(INPUT_GET, 'mail_template_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($mail_template_id)) ? 'Edit Mail Template' : 'Add New Mail Template'; ?></h1>

<?php

$db = new TCData();

$mail_template = (!empty($mail_template_id)) ? $db->load_object(new TCMailTemplate(), $mail_template_id) : new TCMailTemplate();

$form_action = (!empty($mail_template_id)) ? '/admin/actions/update-mail-template.php' : '/admin/actions/create-mail-template.php';
?>

<form id="edit-mail-template" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="mail_template_name">Template Name</label>
    <div class="field">
      <input type="text" name="mail_template_name" value="<?php echo $mail_template->mail_template_name; ?>" />
    </div>
  </div>

  <div class="fieldset textarea">
    <label for="content">Content</label>
    <div class="field">
      <textarea name="content" rows="20" cols="30"><?php echo $mail_template->content; ?></textarea>
    </div>
  </div>

  <input type="hidden" name="mail_template_id" value="<?php echo $mail_template->mail_template_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($mail_template_id)) ? 'Update Mail Template' : 'Add Mail Template'; ?>" />
  </div>
</form>

<div>
  <h3>Mail Template Tokens</h3>
  <h4>Confirm account</h4>
  <table>
    <tr>
      <td>{url}</td>
      <td>The user's account confirmation URL</td>
    </tr>
  </table>
  <h4>Reset password</h4>
  <table>
    <tr>
      <td>{url}</td>
      <td>The user's password reset URL</td>
    </tr>
  </table>
</div>
