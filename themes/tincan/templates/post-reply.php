<?php

use TinCan\template\TCTemplate;

/**
 * Post reply template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread = $data['thread'];
$user = $data['user'];
$page = $data['page'];
$settings = $data['settings'];

$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
?>

<?php
if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
}
?>

<form id="post-reply" action="/actions/create-post.php" method="POST">
  <div class="fieldset textarea">
    <label for="post_content">Reply Content</label>
    <div class="field">
      <textarea name="post_content" rows="20" cols="30"></textarea>
    </div>
  </div>

  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_post" value="Submit reply" />
  </div>
</form>

<?php
  TCTemplate::render('tc-code', $settings['theme'], []);
?>
