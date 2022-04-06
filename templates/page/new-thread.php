<?php
  $page = $data['page'];

  $field_names = array('thread_title', 'thread_post');

  $errors = array();

  foreach ($field_names as $name) {
    if (isset($_GET[$name])) {
      $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
  }

  $board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

  $db = new TCData();

  $board = $db->load_object(new TCBoard(), $board_id);
?>

<h1><?=$page->page_title?></h1>

<?php
  if (!empty($errors)) {
    TCTemplate::render('form-errors', array('errors' => array_values($errors)));
  }
?>

<form action="/actions/create-thread.php" method="POST">
  <label for="thread_title">Thread Title</label>
  <input type="text" name="thread_title" />

  <label for="thread_post">Thread Content</label>
  <textarea name="thread_post" rows="20" cols="30"></textarea>

  <input type="hidden" name="board_id" value="<?=$board->board_id?>" />
  <input type="hidden" name="ajax" value="" />
  <input type="submit" name="submit_thread" value="Submit thread" />
</form>
