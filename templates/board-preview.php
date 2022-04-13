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

<div id="board-<?=$board->board_id?>" class="thread-preview">
  <h2><a href="<?=$data['url']?>"><?=$board->board_name?></a></h2>
  <p><?=$board->description?></p>
</div>
