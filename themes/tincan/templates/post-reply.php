<?php

use TinCan\template\TCTemplate;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;

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

    switch ($error) {
        case TCUser::ERR_NOT_AUTHORIZED:
            $error_msg = 'Your account cannot reply to threads.';
            break;
        case TCObject::ERR_EMPTY_FIELD:
            $error_msg = 'Please enter a longer reply.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Could not save your reply at this time. Please try again later.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
}
?>

<form id="post-reply" action="/actions/create-post.php" method="POST" enctype="multipart/form-data">
  <div class="fieldset textarea">
    <label for="post_content">Reply Content</label>
    <div class="field">
      <textarea name="post_content" rows="20" cols="30"></textarea>
    </div>
  </div>

  <div class="fieldset">
    <label for="post_content">Attachments</label>
    <div class="field">
      <input type="file" name="attachments[]" multiple="multiple" accept="image/png, image/jpeg" />
      Maximum <?php echo $settings['attachment_limit']; ?> files.
    </div>
  </div>

  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_post" value="Submit reply" />
  </div>
</form>
