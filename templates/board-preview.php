<?php
/**
 * Board preview template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $board = $data['board'];
?>

<div id="board-<?=$board->board_id?>" class="board-preview">
  <h2 class="section-subheader"><a href="<?=$data['url']?>"><?=$board->board_name?></a></h2>
  <p><?=$board->description?></p>
</div>
