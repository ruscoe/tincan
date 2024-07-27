<?php

use TinCan\objects\TCBoard;
use TinCan\db\TCData;
use TinCan\objects\TCThread;
use TinCan\objects\TCObject;
use TinCan\template\TCTemplate;

/**
 * Page template for board deletion.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_id = filter_input(INPUT_GET, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

$board = $db->load_object(new TCBoard(), $board_id);

if (empty($board)) {
    $error = TCObject::ERR_NOT_FOUND;
}

// Error handling.
if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Board not found.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Board could not be updated.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

if (!empty($board)) {
    // Get available boards.
    $available_boards = $db->load_objects(new TCBoard());

    $total_threads = $db->count_objects(new TCThread(), [['field' => 'board_id', 'value' => $board->board_id]]); ?>

<h1>Really delete <?php echo $board->get_name(); ?>?</h1>

<form id="delete-board" action="/admin/actions/delete-board.php" method="POST">

    <?php if ($total_threads) { ?>

  <p>This board contains <?php echo $total_threads; ?> thread(s).</p>

  <div class="fieldset thread-options">
    <input type="radio" id="delete_threads" name="thread_fate" value="delete" checked />
    <label for="delete_threads">Delete <?php echo $total_threads; ?> thread(s)</label>
  </div>

        <?php
        // Display option to move threads only if there's at least one other board.
        if (count($available_boards) > 1) { ?>

  <div class="fieldset thread-options">
    <input type="radio" id="move_threads" name="thread_fate" value="move" />
    <label for="move_threads">Move <?php echo $total_threads; ?> thread(s)</label>
  </div>

        <?php } else { ?>
    <div class="fieldset">
      <p>You'll need to create a new board if you'd like to move threads rather than delete them.</p>
    </div>
        <?php } ?>

  <div class="fieldset move-to-board">
    <label for="board_id">Move to board</label>
    <div class="field">
      <select name="move_to_board_id">
            <?php
            foreach ($available_boards as $available_board) {
                if ($available_board->board_id == $board->board_id) {
                    continue;
                }
                echo "<option value=\"{$available_board->board_id}\">{$available_board->board_name}</option>\n";
            }
        ?>
      </select>
    </div>
  </div>

    <?php } ?>

  <input type="hidden" name="board_id" value="<?php echo $board->board_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete Board" />
  </div>
</form>
    <?php
}
