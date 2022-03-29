<?php
  $board_group = $data['board_group'];
  $boards = $data['boards'];
?>

<div id="board-group-<?=$board_group->board_group_id?>" class="board-group">
  <h2><a href="/?page=2&board_group=<?=$board_group->board_group_id?>"><?=$board_group->board_group_name?></a></h2>
  <ul>
    <?php foreach ($boards as $board) { ?>
      <li>
        <h3><a href="/?page=3&board=<?=$board->board_id?>"><?=$board->board_name?></a></h3>
      </li>
    <?php } ?>
  </ul>
</div>