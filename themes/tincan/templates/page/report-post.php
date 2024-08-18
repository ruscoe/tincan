<?php

use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCPost;
use TinCan\objects\TCUser;
use TinCan\template\TCTemplate;
use TinCan\template\TCURL;

/**
 * Report Post page template.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
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

// Check user has permission to delete this post.
if (empty($user) || !$user->can_report_post($post)) {
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
            case TCUser::ERR_NOT_AUTHORIZED:
                $error_msg = 'Your account cannot report posts.';
                break;
            case TCObject::ERR_EMPTY_FIELD:
                $error_msg = 'Please enter a longer reason.';
                break;
            case TCObject::ERR_NOT_SAVED:
                $error_msg = 'Could not save your report at this time. Please try again later.';
                break;
            default:
                $error_msg = $error;
        }

        TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
    } ?>

<form id="report-post" action="/actions/report-post.php" method="POST">
  <div class="fieldset textarea">
    <label for="reason">Reason for reporting</label>
    <div class="field">
      <textarea name="reason" rows="10" cols="40"></textarea>
    </div>
  </div>

  <div class="fieldset button">
    <input type="hidden" name="thread" value="<?php echo $post->thread_id; ?>" />
    <input type="hidden" name="post" value="<?php echo $post->post_id; ?>" />
    <input class="submit-button" type="submit" name="report_post" value="Report post" />
    <input class="submit-button" type="submit" name="cancel" value="Cancel" />
  </div>
</form>
