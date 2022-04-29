<?php

use TinCan\TCBoard;
use TinCan\TCData;
use TinCan\TCThread;

/**
 * Page template for thread editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$user = $data['user'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Thread' : 'Add New Thread'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCThread(), $object_id) : new TCThread();

// Get available boards.
$boards = $db->load_objects(new TCBoard());

$form_action = (!empty($object_id)) ? '/admin/actions/update-object.php' : '/admin/actions/create-thread.php';
?>

<form id="edit-thread" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="thread_title">Thread Title</label>
    <div class="field">
      <input type="text" name="thread_title" value="<?php echo $object->thread_title; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="board_id">Board</label>
    <div class="field">
      <select name="board_id">
        <?php
          foreach ($boards as $board) {
            $selected = ($board->board_id == $object->board_id) ? ' selected' : '';
            echo "<option value=\"{$board->board_id}\"{$selected}>{$board->board_name}</option>\n";
          }
        ?>
      </select>
    </div>
  </div>

  <input type="hidden" name="object_id" value="<?php echo $object->thread_id; ?>" />
  <input type="hidden" name="created_by_user" value="<?php echo $user->user_id; ?>" />
  <input type="hidden" name="updated_by_user" value="<?php echo $user->user_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($object_id)) ? 'Update Thread' : 'Add Thread'; ?>" />
  </div>
</form>
