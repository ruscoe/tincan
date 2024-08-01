<?php

use TinCan\db\TCData;
use TinCan\objects\TCPost;
use TinCan\template\TCTemplate;
use TinCan\template\TCURL;

/**
 * Edit Post page template.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
$page_number = filter_input(INPUT_GET, 'page_number', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$page = $data['page'];
$settings = $data['settings'];
$user = $data['user'];

$db = new TCData();

$post = $db->load_object(new TCPost(), $post_id);

if (empty($post)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

// Check user has permission to edit this post.
if (empty($user) || !$user->can_edit_post($post)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $post, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php

if (!empty($error)) {

    switch ($error) {
        case TCObject::ERR_NOT_AUTHORIZED:
            $error_msg = 'You cannot edit this post.';
            break;
        case TCObject::ERR_NOT_FOUND:
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Post could not be edited at this time. Please try again later.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
}

if (!empty($user) && $user->can_edit_post($post)) {
    ?>

<form id="update-post" action="/actions/update-post.php" method="POST">
  <div class="fieldset textarea">
    <label for="post_content">Reply Content</label>
    <div class="field">
      <textarea name="post_content" rows="20" cols="30"><?php echo $post->content; ?></textarea>
    </div>
  </div>

  <input type="hidden" name="page_number" value="<?php echo $page_number; ?>" />
  <input type="hidden" name="post" value="<?php echo $post->post_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_post" value="Save changes" />
  </div>
</form>

    <?php
    TCTemplate::render('tc-code', $settings['theme'], []);
}
