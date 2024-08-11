<?php

use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\template\TCTemplate;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;
use TinCan\objects\TCObject;
use TinCan\objects\TCThread;

/**
 * New thread page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$page = $data['page'];
$user = $data['user'];

$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
$title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING);
$content = filter_input(INPUT_GET, 'content', FILTER_SANITIZE_STRING);

$db = new TCData();

if (!empty($board_id)) {
    $board = $db->load_object(new TCBoard(), $board_id);
}

// 404 if board does not exist.
if (empty($board)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);

// Check user has permission to create a new thread.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
    ?>

<div>
  Please <a href="<?php echo TCURL::create_url($settings['page_log_in']); ?>">log in</a>
  or <a href="<?php echo TCURL::create_url($settings['page_create_account']); ?>">create an account</a> if you'd like to do that!
</div>

    <?php
} else {
    TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]); ?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

    <?php
    if (!empty($error)) {

        switch ($error) {
            case TCUser::ERR_NOT_AUTHORIZED:
                $error_msg = 'Your account cannot create new threads.';
                break;
            case TCThread::ERR_TITLE_SHORT:
                $error_msg = 'Please enter a longer title.';
                break;
            case TCThread::ERR_TITLE_LONG:
                $error_msg = 'Please enter a shorter title.';
                break;
            case TCObject::ERR_EMPTY_FIELD:
                $error_msg = 'Please enter a longer post.';
                break;
            case TCObject::ERR_NOT_SAVED:
                $error_msg = 'Could not save your thread at this time. Please try again later.';
                break;
            default:
                $error_msg = $error;
        }

        TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
    } ?>

<form id="create-thread" action="/actions/create-thread.php" method="POST" enctype="multipart/form-data">
  <div class="fieldset">
    <label for="thread_title">Thread Title</label>
    <div class="field">
      <input class="text-input" type="text" name="thread_title" value="<?php echo $title; ?>" />
    </div>
  </div>

  <div class="fieldset textarea">
    <label for="post_content">Thread Content</label>
    <div class="field">
      <textarea name="post_content" rows="10" cols="50"><?php echo $content; ?></textarea>
    </div>
  </div>

  <div class="fieldset">
    <label for="post_content">Attachments</label>
    <div class="field">
      <input type="file" name="attachments[]" multiple="multiple" accept="image/png, image/jpeg" />
      Maximum <?php echo $settings['attachment_limit']; ?> files.
    </div>
  </div>

  <input type="hidden" name="board_id" value="<?php echo $board->board_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_thread" value="Submit thread" />
  </div>
</form>

    <?php
}
