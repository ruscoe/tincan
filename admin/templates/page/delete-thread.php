<?php

use TinCan\TCData;
use TinCan\TCPost;
use TinCan\TCThread;
use TinCan\TCUser;

/**
 * Page template for thread deletion.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

if (!empty($thread)) {
  $total_posts = $db->count_objects(new TCPost(), [['field' => 'thread_id', 'value' => $thread->thread_id]]);
  ?>

<h1>Really delete <?php echo $thread->get_name(); ?>?</h1>

<p><?php echo $total_posts; ?> posts within this thread will also be deleted.</p>

<form id="delete-object" action="/admin/actions/delete-thread.php" method="POST">
  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete" />
  </div>
</form>
<?php
} else {
    ?>
  <h1>Thread not found</h1>
  <p>This thread either never existed or has already been deleted.</p>
  <?php
  }
?>
