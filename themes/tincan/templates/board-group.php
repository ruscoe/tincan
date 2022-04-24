<?php

use TinCan\TCURL;

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

  $board_group_url = TCURL::create_url($settings['page_board_group'], [
    'board_group' => $board_group->board_group_id,
  ]);
?>

<div id="board-group-<?php echo $board_group->board_group_id; ?>" class="board-group">
  <h2><a href="<?php echo $board_group_url; ?>"><?php echo $board_group->board_group_name; ?></a></h2>
  <ul>
    <?php foreach ($boards as $board) {
  $board_url = TCURL::create_url($settings['page_board'], ['board' => $board->board_id]); ?>
      <li>
        <h3 class="section-subheader"><a href="<?php echo $board_url; ?>"><?php echo $board->board_name; ?></a></h3>
        <p><?php echo $board->description; ?></p>
      </li>
    <?php
} ?>
  </ul>
</div>
