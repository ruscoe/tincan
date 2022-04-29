<?php

use TinCan\TCBoardGroup;
use TinCan\TCData;

/**
 * Page template for admin board group editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Board Group' : 'Add New Board Group'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCBoardGroup(), $object_id) : new TCBoardGroup();

$form_action = (!empty($object_id)) ? '/admin/actions/update-object.php' : '/admin/actions/create-board-group.php';
?>

<form id="edit-board-group" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="board_group_name">Board Group Name</label>
    <div class="field">
      <input type="text" name="board_group_name" value="<?php echo $object->board_group_name; ?>" />
    </div>
  </div>

  <input type="hidden" name="object_id" value="<?php echo $object->board_group_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($object_id)) ? 'Update Board Group' : 'Add Board Group'; ?>" />
  </div>
</form>
