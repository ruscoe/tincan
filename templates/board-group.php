<?php
/**
 * Board group template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $settings = $data['settings'];
  $board_group = $data['board_group'];
  $boards = $data['boards'];
?>

<div id="board-group-<?php echo $board_group->board_group_id; ?>" class="board-group">
  <h2><a href="/?page=<?php echo $settings['page_board_group']; ?>&board_group=<?php echo $board_group->board_group_id; ?>"><?php echo $board_group->board_group_name; ?></a></h2>
  <ul>
    <?php foreach ($boards as $board) { ?>
      <li>
        <h3 class="section-subheader"><a href="/?page=<?php echo $settings['page_board']; ?>&board=<?php echo $board->board_id; ?>"><?php echo $board->board_name; ?></a></h3>
        <p><?php echo $board->description; ?></p>
      </li>
    <?php } ?>
  </ul>
</div>
