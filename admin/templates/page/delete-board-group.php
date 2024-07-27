<?php

use TinCan\db\TCData;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCObject;
use TinCan\template\TCTemplate;

/**
 * Page template for board deletion.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_group_id = filter_input(INPUT_GET, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);

if (empty($board_group)) {
    $error = TCObject::ERR_NOT_FOUND;
}

if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Board group not found.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

if (!empty($board_group)) {

    // Get available board groups.
    $available_board_groups = $db->load_objects(new TCBoardGroup());

    $total_boards = $db->count_objects(new TCBoard(), [['field' => 'board_group_id', 'value' => $board_group->board_group_id]]); ?>

<h1>Really delete <?php echo $board_group->get_name(); ?>?</h1>

<form id="delete-board-group" action="/admin/actions/delete-board-group.php" method="POST">

    <?php if ($total_boards) { ?>

  <p>This board group contains <?php echo $total_boards; ?> board(s).</p>

  <div class="fieldset board-options">
    <input type="radio" id="delete_boards" name="board_fate" value="delete" checked />
    <label for="delete_boards">Delete <?php echo $total_boards; ?> board(s)</label>
  </div>

        <?php
          // Display option to move boards only if there's at least one other board group.
          if (count($available_board_groups) > 1) { ?>

  <div class="fieldset board-options">
    <input type="radio" id="move_boards" name="board_fate" value="move" />
    <label for="move_boards">Move <?php echo $total_boards; ?> board(s)</label>
  </div>

        <?php } else { ?>
    <div class="fieldset">
      <p>You'll need to create a new board group if you'd like to move boards rather than delete them.</p>
    </div>
        <?php } ?>

  <div class="fieldset move-to-board-group">
    <label for="board_id">Move to board group</label>
    <div class="field">
      <select name="move_to_board_group_id">
            <?php
              foreach ($available_board_groups as $available_board_group) {
                  if ($available_board_group->board_group_id == $board_group->board_group_id) {
                      continue;
                  }
                  echo "<option value=\"{$available_board_group->board_group_id}\">{$available_board_group->board_group_name}</option>\n";
              }
        ?>
      </select>
    </div>
  </div>

    <?php } ?>

  <input type="hidden" name="board_group_id" value="<?php echo $board_group->board_group_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete Board Group" />
  </div>
</form>
    <?php
}
