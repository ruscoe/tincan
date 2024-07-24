<?php

use TinCan\db\TCData;
use TinCan\objects\TCThread;
use TinCan\template\TCTemplate;
use TinCan\template\TCURL;

/**
 * Delete Thread page template.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];
$user = $data['user'];

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

// 404 if the thread doesn't exist.
if (empty($thread)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

// 404 if user doesn't have permission to delete this thread.
if (empty($user) || !$user->can_delete_thread($thread)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $thread, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<div class="confirmation-box">

  <p>Really delete this thread?</p>

  <form id="delete-thread" action="/actions/delete-thread.php" method="POST">
    <div class="fieldset button">
      <input type="hidden" name="board" value="<?php echo $thread->board_id; ?>" />
      <input type="hidden" name="thread" value="<?php echo $thread->thread_id; ?>" />
      <input class="submit-button" type="submit" name="delete_thread" value="Delete thread" />
      <input class="submit-button" type="submit" name="cancel" value="Cancel" />
    </div>
  </form>

</div>
