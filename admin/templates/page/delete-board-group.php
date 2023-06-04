<?php

use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;

/**
 * Page template for board deletion.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_group_id = filter_input(INPUT_GET, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$board_group = $db->load_object(new TCBoardGroup(), $board_group_id);

// Get available board groups.
$available_board_groups = $db->load_objects(new TCBoardGroup());

if (!empty($board_group)) {
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
} else {
    ?>
  <h1>Board Group not found</h1>
  <p>This board group either never existed or has already been deleted.</p>
    <?php
}
?>

<script type="text/javascript">
  (function($) {
    $(document).ready(function() {
      $('input[name=board_fate]').change(function() {
        console.log($(this).val());
        if ($(this).val() == 'move') {
          $('.fieldset.move-to-board-group').css('display', 'flex');
        }
        else {
          $('.fieldset.move-to-board-group').hide();
        }
      });
    });
  })(jQuery);
</script>
