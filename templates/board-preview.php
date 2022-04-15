<?php
/**
 * Board preview template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $board = $data['board'];
?>

<div id="board-<?php echo $board->board_id; ?>" class="board-preview">
  <h2 class="section-subheader"><a href="<?php echo $data['url']; ?>"><?php echo $board->board_name; ?></a></h2>
  <p><?php echo $board->description; ?></p>
</div>
