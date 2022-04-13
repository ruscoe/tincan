<?php
/**
 * Post reply template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $thread = $data['thread'];
  $user = $data['user'];

  $field_names = array('post_content');

  $errors = array();

  foreach ($field_names as $name) {
      if (isset($_GET[$name])) {
          $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
      }
  }
?>

<?php
  if (!empty($errors)) {
      TCTemplate::render('form-errors', array('errors' => array_values($errors)));
  }
?>

<form action="/actions/create-post.php" method="POST">
  <label for="thread_title">Reply Content</label>
  <textarea name="post_content" rows="20" cols="30"></textarea>

  <input type="hidden" name="thread_id" value="<?=$thread->thread_id?>" />
  <input type="hidden" name="ajax" value="" />
  <input type="submit" name="submit_post" value="Submit reply" />
</form>

<?php
  TCTemplate::render('tc-code', array());
?>
