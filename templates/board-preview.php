<?php
  $board = $data['board'];
?>

<div id="board-<?=$board->board_id?>" class="thread-preview">
  <h2><a href="<?=$data['url']?>"><?=$board->board_name?></a></h2>
  <p><?=$board->description?></p>
</div>
