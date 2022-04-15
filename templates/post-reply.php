<?php
/**
 * Post reply template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $thread = $data['thread'];
  $user = $data['user'];

  $field_names = ['post_content'];

  $errors = [];

  foreach ($field_names as $name) {
    if (isset($_GET[$name])) {
      $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
  }
?>

<?php
  if (!empty($errors)) {
    TCTemplate::render('form-errors', ['errors' => array_values($errors)]);
  }
?>

<form id="post-reply" action="/actions/create-post.php" method="POST">
  <div class="fieldset">
    <label for="thread_title">Reply Content</label>
    <textarea name="post_content" rows="20" cols="30"></textarea>
  </div>

  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />
  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="submit_post" value="Submit reply" />
  </div>
</form>

<?php
  TCTemplate::render('tc-code', []);
?>
