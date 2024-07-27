<?php

use TinCan\db\TCData;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCObject;
use TinCan\template\TCTemplate;

/**
 * Page template for admin board editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_id = filter_input(INPUT_GET, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
?>

<h1><?php echo (!empty($board_id)) ? 'Edit Board' : 'Add New Board'; ?></h1>

<?php

// Error handling.
if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Board group not found.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Board group could not be updated.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

$db = new TCData();
$settings = $db->load_settings();

$board = (!empty($board_id)) ? $db->load_object(new TCBoard(), $board_id) : new TCBoard();

// Get available board groups.
$board_groups = $db->load_objects(new TCBoardGroup());

$form_action = (!empty($board_id)) ? '/admin/actions/update-board.php' : '/admin/actions/create-board.php';
?>

<?php if (empty($board_groups)) { ?>
  <p>You'll need to <a href="/admin/index.php?page=<?php echo $settings['admin_page_board_groups']; ?>" title="Board Groups">create a board group</a> first.</p>
<?php } else { ?>

<form id="edit-board" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="board_name">Board Name</label>
    <div class="field">
      <input type="text" name="board_name" value="<?php echo $board->board_name; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="board_group_id">Board Group</label>
    <div class="field">
      <select name="board_group_id">
      <?php
        foreach ($board_groups as $board_group) {
            $selected = ($board_group->board_group_id == $board->board_group_id) ? ' selected' : '';
            echo "<option value=\"{$board_group->board_group_id}\"{$selected}>{$board_group->board_group_name}</option>\n";
        }
    ?>
      </select>
    </div>
  </div>

  <input type="hidden" name="board_id" value="<?php echo $board->board_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($board_id)) ? 'Update Board' : 'Add Board'; ?>" />
  </div>
</form>

<?php } ?>
