<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCThread;
use TinCan\TCPost;

/**
 * Page template for thread editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];

$thread_id = filter_input(INPUT_GET, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($thread_id)) ? 'Edit Thread' : 'Add New Thread'; ?></h1>

<?php

$db = new TCData();

$thread = (!empty($thread_id)) ? $db->load_object(new TCThread(), $thread_id) : new TCThread();

// Get available boards.
$boards = $db->load_objects(new TCBoard());

$form_action = (!empty($thread_id)) ? '/admin/actions/update-thread.php' : '/admin/actions/create-thread.php';
?>

<form id="edit-thread" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="thread_title">Thread Title</label>
    <div class="field">
      <input class="text-input" type="text" name="thread_title" value="<?php echo $thread->thread_title; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="board_id">Board</label>
    <div class="field">
      <select name="board_id">
        <?php
          foreach ($boards as $board) {
              $selected = ($board->board_id == $thread->board_id) ? ' selected' : '';
              echo "<option value=\"{$board->board_id}\"{$selected}>{$board->board_name}</option>\n";
          }
?>
      </select>
    </div>
  </div>

  <?php
  $first_post = $db->load_object(new TCPost(), $thread->first_post_id);
?>

  <div class="fieldset textarea">
    <label for="content">Content</label>
    <div class="field">
      <textarea name="content" rows="20" cols="30"><?php echo (!empty($first_post)) ? $first_post->content : ''; ?></textarea>
    </div>
  </div>

  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />
  <input type="hidden" name="created_by_user" value="<?php echo $user->user_id; ?>" />
  <input type="hidden" name="updated_by_user" value="<?php echo $user->user_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($thread_id)) ? 'Update Thread' : 'Add Thread'; ?>" />
  </div>
</form>
