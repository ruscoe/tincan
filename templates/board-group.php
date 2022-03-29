<?php
  $board_group = $data['board_group'];
  $boards = $data['boards'];
?>
sdfsdf
<div id="board-group-<?=$board_group->board_group_id?>" class="board-group">
  <h2><a href="#"><?=$board_group->board_group_name?></a></h2>
  <ul>
    <?php foreach ($boards as $board) { ?>
      <li>
        <h3><?=$board->board_name?></h3>
      </li>
    <?php } ?>
  </ul>
</div>
