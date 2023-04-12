<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCTemplate;
use TinCan\TCURL;
use TinCan\TCUser;

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
$slug = $data['slug'];

$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

if (!empty($board_id)) {
    $board = $db->load_object(new TCBoard(), $board_id);
} elseif (!empty($slug)) {
    $matched_boards = $db->load_objects(new TCBoard(), null, [['field' => 'slug', 'value' => $slug]]);
    $board = reset($matched_boards);
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
      TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
  } ?>

<form id="create-thread" action="/actions/create-thread.php" method="POST">
  <div class="fieldset">
    <label for="thread_title">Thread Title</label>
    <div class="field">
      <input class="text-input" type="text" name="thread_title" />
    </div>
  </div>

  <div class="fieldset textarea">
    <label for="post_content">Thread Content</label>
    <div class="field">
      <textarea name="post_content" rows="10" cols="50"></textarea>
    </div>
  </div>

  <input type="hidden" name="board_id" value="<?php echo $board->board_id; ?>" />
  <input class="ajax" type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_thread" value="Submit thread" />
  </div>
</form>

<?php
  TCTemplate::render('tc-code', $settings['theme'], []);
}
?>
