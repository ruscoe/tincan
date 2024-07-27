<?php

use TinCan\db\TCData;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;
use TinCan\objects\TCObject;
use TinCan\template\TCTemplate;

/**
 * Page template for thread deletion.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

if (empty($thread)) {
    $error = TCObject::ERR_NOT_FOUND;
}

// Error handling.
if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Thread not found.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Thread could not be updated.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

if (!empty($thread)) {
    $total_posts = $db->count_objects(new TCPost(), [['field' => 'thread_id', 'value' => $thread->thread_id]]); ?>

<h1>Really delete <?php echo $thread->get_name(); ?>?</h1>

<p><?php echo $total_posts; ?> posts within this thread will also be deleted.</p>

<form id="delete-object" action="/admin/actions/delete-thread.php" method="POST">
  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete Thread" />
  </div>
</form>
    <?php
}
