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

$board_group_url = null;
if ($settings['enable_urls']) {
  $board_group_url = TCURL::create_friendly_url($settings['base_url_board_groups'], $board_group);
} else {
  $board_group_url = TCURL::create_url($settings['page_board_group'], [
    'board_group' => $board_group->board_group_id,
  ]);
}
?>

<div id="board-group-<?php echo $board_group->board_group_id; ?>" class="board-group">
  <h2><a href="<?php echo $board_group_url; ?>"><?php echo $board_group->board_group_name; ?></a></h2>
  <ul>
    <?php
    $board_url = null;
    foreach ($boards as $board) {
      if ($settings['enable_urls']) {
        $board_url = TCURL::create_friendly_url($settings['base_url_boards'], $board);
      } else {
        $board_url = TCURL::create_url($settings['page_board'], [
          'board' => $board->board_id,
        ]);
      } ?>
      <li>
        <h3 class="section-subheader"><a href="<?php echo $board_url; ?>"><?php echo $board->board_name; ?></a></h3>
        <p><?php echo $board->description; ?></p>
      </li>
    <?php
    } ?>
  </ul>
</div>
