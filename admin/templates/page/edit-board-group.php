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
$board_group_id = filter_input(INPUT_GET, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($board_group_id)) ? 'Edit Board Group' : 'Add New Board Group'; ?></h1>

<?php

$db = new TCData();

$board_group = (!empty($board_group_id)) ? $db->load_object(new TCBoardGroup(), $board_group_id) : new TCBoardGroup();

$form_action = (!empty($board_group_id)) ? '/admin/actions/update-board-group.php' : '/admin/actions/create-board-group.php';
?>

<form id="edit-board-group" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="board_group_name">Board Group Name</label>
    <div class="field">
      <input type="text" name="board_group_name" value="<?php echo $board_group->board_group_name; ?>" />
    </div>
  </div>

  <input type="hidden" name="board_group_id" value="<?php echo $board_group->board_group_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($board_group_id)) ? 'Update Board Group' : 'Add Board Group'; ?>" />
  </div>
</form>
