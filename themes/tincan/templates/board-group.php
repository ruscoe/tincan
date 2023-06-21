<?php

use TinCan\template\TCURL;

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

$url_id = ($settings['enable_urls']) ? $settings['base_url_board_groups'] : $settings['page_board_group'];
$board_group_url = TCURL::create_url($url_id, ['board_group' => $board_group->board_group_id], $settings['enable_urls'], $board_group->get_slug());
?>

<div id="board-group-<?php echo $board_group->board_group_id; ?>" class="board-group">
  <h2><a href="<?php echo $board_group_url; ?>"><?php echo $board_group->board_group_name; ?></a></h2>
  <ul>
    <?php
    if (!empty($boards)) {
        foreach ($boards as $board) {
            $url_id = ($settings['enable_urls']) ? $settings['base_url_boards'] : $settings['page_board'];
            $board_url = TCURL::create_url($url_id, ['board' => $board->board_id], $settings['enable_urls'], $board->get_slug()); ?>
        <li>
          <h3 class="section-subheader"><a href="<?php echo $board_url; ?>"><?php echo $board->board_name; ?></a></h3>
          <p><?php echo $board->description; ?></p>
        </li>
            <?php
        }
    } else { ?>
      <li><p>There are no boards here!</p></li>
    <?php } ?>
  </ul>
</div>
