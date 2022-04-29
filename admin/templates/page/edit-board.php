<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;

/**
 * Page template for admin board editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Board' : 'Add New Board'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCBoard(), $object_id) : new TCBoard();

// Get available board groups.
$board_groups = $db->load_objects(new TCBoardGroup());

$form_action = (!empty($object_id)) ? '/admin/actions/update-object.php' : '/admin/actions/create-board.php';
?>

<form id="edit-board" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="board_name">Board Name</label>
    <div class="field">
      <input type="text" name="board_name" value="<?php echo $object->board_name; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="board_group_id">Board Group</label>
    <div class="field">
      <select name="board_group_id">
        <?php
          foreach ($board_groups as $board_group) {
            $selected = ($board_group->board_group_id == $object->board_group_id) ? ' selected' : '';
            echo "<option value=\"{$board_group->board_group_id}\"{$selected}>{$board_group->board_group_name}</option>\n";
          }
        ?>
      </select>
    </div>
  </div>

  <input type="hidden" name="object_id" value="<?php echo $object->board_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($object_id)) ? 'Update Board' : 'Add Board'; ?>" />
  </div>
</form>
