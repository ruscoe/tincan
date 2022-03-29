<?php
  $page = $data['page'];

  $board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

  $db = new TCData();

  $board = $db->load_object(new TCBoard(), $board_id);
?>

<h1><?=$page->page_title?></h1>

<form action="/actions/create-thread.php" method="POST">
  <input type="text" name="thread_title" />
  <textarea name="thread_post" rows="20" cols="30"></textarea>
  <input type="hidden" name="board_id" value="<?=$board->board_id?>" />
  <input type="submit" name="submit_thread" value="Submit thread" />
</form>
